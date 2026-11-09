<?php

namespace App\Http\Controllers\Admin\Crm;

use App\Http\Controllers\Controller;
use App\Http\Requests\CrmPaymentStoreRequest;
use App\Http\Requests\CrmPaymentUpdateRequest;
use App\Jobs\SyncPaymentToGoogleSheetJob;
use App\Models\CrmLead;
use App\Models\CrmPayment;
use App\Services\CrmFxRateService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CrmPaymentController extends Controller
{
    public function index(Request $request)
    {
        $currency = $request->query('currency');
        $search = $request->query('search');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = CrmPayment::with('lead')
            ->orderBy('paid_on', 'desc')
            ->orderBy('id', 'desc');

        if (!empty($currency)) {
            $query->where('currency', $currency);
        }

        if (!empty($startDate)) {
            $query->whereDate('paid_on', '>=', $startDate);
        }

        if (!empty($endDate)) {
            $query->whereDate('paid_on', '<=', $endDate);
        }

        if (!empty($search)) {
            $query->whereHas('lead', function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%");
            });
        }

        $payments = $query->paginate(20)->withQueryString();
        $totalInr = (float) (clone $query)->sum('received_inr');

        return view('admin.crm.payments.index', compact(
            'payments',
            'totalInr',
            'currency',
            'search',
            'startDate',
            'endDate'
        ));
    }

    public function store(CrmPaymentStoreRequest $request, $leadId)
    {
        $amount = (float) $request->validated('amount');

        // Date defaults to today if not provided
        $paidOn = $request->filled('paid_on')
            ? Carbon::parse($request->paid_on)
            : Carbon::today();

        $payment = DB::transaction(function () use ($leadId, $amount, $paidOn, $request) {
            // Lock the lead record to prevent concurrent race conditions
            $lead = CrmLead::lockForUpdate()->findOrFail($leadId);

            $dealAmount = (float) ($lead->deal_amount ?? 0);
            if ($dealAmount <= 0) {
                throw ValidationException::withMessages([
                    'amount' => ['Cannot record payment for a deal with zero, negative, or missing deal amount.'],
                ]);
            }

            // Calculate actual total received directly from database table under pessimistic lock
            $actualReceived = (float) CrmPayment::where('lead_id', $lead->id)->lockForUpdate()->sum('amount');
            $remaining = round(max(0.0, $dealAmount - $actualReceived), 2);

            if (round($amount, 2) > $remaining) {
                $sym = $lead->currency_symbol;
                throw ValidationException::withMessages([
                    'amount' => ["Payment exceeds remaining balance! Remaining payable is {$sym}" . number_format($remaining, 2) . "."],
                ]);
            }

            // Duplicate submission guard: prevent identical payment submitted within last 5 seconds for the same lead
            $recentDuplicate = CrmPayment::where('lead_id', $lead->id)
                ->where('amount', $amount)
                ->where('paid_on', $paidOn->toDateString())
                ->where('created_at', '>=', Carbon::now()->subSeconds(5))
                ->lockForUpdate()
                ->first();

            if ($recentDuplicate) {
                throw ValidationException::withMessages([
                    'amount' => ['Duplicate payment submission detected. Please do not submit twice.'],
                ]);
            }

            // Currency is strictly the lead's currency
            $currency = strtoupper($lead->currency);

            // FX Rate: use user-supplied rate if provided, otherwise fetch today's rate
            $fxService = app(CrmFxRateService::class);
            $fxRate = $request->filled('fx_rate')
                ? (float) $request->fx_rate
                : $fxService->getRate($currency, $paidOn);

            // In INR, rate is strictly 1
            if ($currency === 'INR') {
                $fxRate = 1.0000;
            }

            // Calculate received_inr and permanently lock
            $receivedInr = round($amount * $fxRate, 2);

            return CrmPayment::create([
                'lead_id' => $lead->id,
                'paid_on' => $paidOn->toDateString(),
                'currency' => $currency,
                'amount' => $amount,
                'fx_rate' => $fxRate,
                'received_inr' => $receivedInr,
                'note' => $request->validated('note'),
            ]);
        });

        // Push to Google Sheets
        dispatch(new SyncPaymentToGoogleSheetJob($payment->id, 'create'));

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment recorded successfully and exchange rate locked!',
                'payment' => $payment,
            ]);
        }

        $lead = $payment->lead;
        return redirect()->route('admin.crm.leads.show', $lead->id)
            ->with('success', "Payment of {$lead->currency_symbol}" . number_format($amount, 2) . " recorded successfully (FX Rate: {$payment->fx_rate}, Received: ₹" . number_format($payment->received_inr, 2) . ")!");
    }

    public function update(CrmPaymentUpdateRequest $request, $id)
    {
        $payment = CrmPayment::with('lead')->findOrFail($id);
        $data = $request->validated();

        $amount = (float) $data['amount'];
        $fxRate = (float) $data['fx_rate'];
        $currency = strtoupper($payment->currency);

        if ($currency === 'INR') {
            $fxRate = 1.0000;
        }

        $receivedInr = round($amount * $fxRate, 2);

        $payment->update([
            'paid_on' => $data['paid_on'],
            'amount' => $amount,
            'fx_rate' => $fxRate,
            'received_inr' => $receivedInr,
            'note' => $data['note'] ?? null,
        ]);

        dispatch(new SyncPaymentToGoogleSheetJob($payment->id, 'update'));

        return redirect()->back()
            ->with('success', "Payment #{$payment->id} updated successfully!");
    }

    public function destroy($id)
    {
        $payment = CrmPayment::findOrFail($id);
        $leadId = $payment->lead_id;
        $payment->delete();

        dispatch(new SyncPaymentToGoogleSheetJob($id, 'delete'));

        return redirect()->back()
            ->with('success', 'Payment deleted successfully.');
    }

    /**
     * Export payments table to CSV
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $currency = $request->query('currency');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = CrmPayment::with('lead')
            ->orderBy('paid_on', 'desc');

        if (!empty($currency)) {
            $query->where('currency', $currency);
        }
        if (!empty($startDate)) {
            $query->whereDate('paid_on', '>=', $startDate);
        }
        if (!empty($endDate)) {
            $query->whereDate('paid_on', '<=', $endDate);
        }

        $payments = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="crm_payments_' . date('Y-m-d_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($payments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Payment ID',
                'Date',
                'Business Name',
                'Currency',
                'Amount Received',
                'FX Rate Used',
                'Received INR',
                'Note',
            ]);

            foreach ($payments as $p) {
                fputcsv($file, [
                    $p->id,
                    $p->paid_on ? $p->paid_on->format('Y-m-d') : '',
                    $p->lead?->business_name ?? 'N/A',
                    $p->currency,
                    $p->amount,
                    $p->fx_rate,
                    $p->received_inr,
                    $p->note ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
