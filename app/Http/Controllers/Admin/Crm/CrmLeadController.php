<?php

namespace App\Http\Controllers\Admin\Crm;

use App\Http\Controllers\Controller;
use App\Http\Requests\CrmLeadStoreRequest;
use App\Http\Requests\CrmLeadUpdateRequest;
use App\Jobs\SyncLeadToGoogleSheetJob;
use App\Models\CrmLead;
use App\Models\CrmPayment;
use App\Services\CrmFxRateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CrmLeadController extends Controller
{
    public function index(Request $request)
    {
        $viewMode = $request->query('view', 'table'); // 'table' or 'kanban'
        $market = $request->query('market');
        $service = $request->query('service');
        $channel = $request->query('channel');
        $search = $request->query('search');
        $statusFilter = $request->query('status');

        $query = CrmLead::with('payments')
            ->byMarket($market)
            ->byService($service)
            ->search($search);

        if (!empty($channel)) {
            $query->where('contact_channel', $channel);
        }

        if (!empty($statusFilter)) {
            $query->where('status', $statusFilter);
        }

        $allStatuses = ['New', 'Contacted', 'Proposal', 'Won', 'Lost'];

        // Funnel stats for Top bar matching Google Sheet Canvas
        $totalLeadsCount = CrmLead::count();
        $contactedCount = CrmLead::where('status', '!=', 'New')->count();
        $interestedCount = CrmLead::where('response', 'Interested')->count();
        $wonCount = CrmLead::where('status', 'Won')->count();
        $wonInrK = round(CrmLead::where('status', 'Won')->where('currency', 'INR')->sum('deal_amount') / 1000);
        $wonAedK = round(CrmLead::where('status', 'Won')->where('currency', 'AED')->sum('deal_amount') / 1000);
        $wonRevenueBadge = "₹{$wonInrK}k • AED {$wonAedK}k";

        $fxService = app(CrmFxRateService::class);
        $aedRate = $fxService->getRate('AED');
        $usdRate = $fxService->getRate('USD');

        if ($viewMode === 'table') {
            $leads = (clone $query)->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
            $kanbanColumns = [];
        } else {
            $leadsList = (clone $query)->orderBy('updated_at', 'desc')->get();
            $kanbanColumns = [];
            foreach ($allStatuses as $st) {
                $kanbanColumns[$st] = $leadsList->where('status', $st)->values();
            }
            $leads = $leadsList;
        }

        // Markets and services list for filter dropdowns
        $markets = ['India', 'Dubai', 'Other'];
        $services = ['Website', 'Laravel', 'Automation'];
        $channels = ['WhatsApp', 'Instagram', 'Email', 'Call'];

        return view('admin.crm.leads.index', compact(
            'viewMode',
            'leads',
            'kanbanColumns',
            'allStatuses',
            'markets',
            'services',
            'channels',
            'market',
            'service',
            'channel',
            'search',
            'statusFilter',
            'totalLeadsCount',
            'contactedCount',
            'interestedCount',
            'wonCount',
            'wonRevenueBadge',
            'aedRate',
            'usdRate'
        ));
    }

    public function store(CrmLeadStoreRequest $request)
    {
        $data = $request->validated();
        $lead = CrmLead::create($data);

        // Queue Google Sheets push
        dispatch(new SyncLeadToGoogleSheetJob($lead->id, 'create'));

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Lead created successfully!',
                'lead' => $lead,
            ]);
        }

        return redirect()->route('admin.crm.leads.index')
            ->with('success', "Lead '{$lead->business_name}' added successfully!");
    }

    public function show($id)
    {
        $lead = CrmLead::with(['payments' => function ($q) {
            $q->orderBy('paid_on', 'desc')->orderBy('id', 'desc');
        }])->findOrFail($id);

        $fxService = app(CrmFxRateService::class);
        $todayFxRate = $fxService->getRate($lead->currency);

        return view('admin.crm.leads.show', compact('lead', 'todayFxRate'));
    }

    public function update(CrmLeadUpdateRequest $request, $id)
    {
        $data = $request->validated();

        $lead = DB::transaction(function () use ($data, $id) {
            $lead = CrmLead::lockForUpdate()->findOrFail($id);

            // Check whether payments exist under lock
            $hasPayments = \App\Models\CrmPayment::where('lead_id', $lead->id)->lockForUpdate()->exists();

            // Explicit rejection if payments exist and requested currency differs
            if ($hasPayments && isset($data['currency']) && strtoupper($data['currency']) !== strtoupper($lead->currency)) {
                throw ValidationException::withMessages([
                    'currency' => ["Currency cannot be changed because this deal already has recorded payments in {$lead->currency}. To preserve accounting integrity, deal currency is permanently locked."]
                ]);
            }

            // Deal amount cannot be reduced below already received payments
            if (isset($data['deal_amount'])) {
                $newDealAmount = (float) $data['deal_amount'];
                $actualReceived = (float) \App\Models\CrmPayment::where('lead_id', $lead->id)->lockForUpdate()->sum('amount');
                if ($actualReceived > 0 && round($newDealAmount, 2) < round($actualReceived, 2)) {
                    $sym = $lead->currency_symbol;
                    throw ValidationException::withMessages([
                        'deal_amount' => ["Deal amount cannot be reduced below the amount already received ({$sym}" . number_format($actualReceived, 2) . ")."]
                    ]);
                }
            }

            // Permanently lock status for Won/Lost leads
            if (in_array($lead->status, ['Won', 'Lost'])) {
                unset($data['status']);
            }

            $lead->update($data);

            return $lead;
        });

        dispatch(new SyncLeadToGoogleSheetJob($lead->id, 'update'));

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Lead updated successfully!',
                'lead' => $lead,
            ]);
        }

        return redirect()->route('admin.crm.leads.show', $lead->id)
            ->with('success', 'Lead details updated successfully!');
    }

    /**
     * AJAX endpoint for drag-drop Kanban & Table status updates
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:New,Contacted,Proposal,Won,Lost',
            'deal_amount' => 'nullable|numeric|min:0',
        ]);

        $lead = DB::transaction(function () use ($request, $id) {
            $lead = CrmLead::lockForUpdate()->findOrFail($id);

            // Disallow status transitions for Won or Lost leads
            if (in_array($lead->status, ['Won', 'Lost']) && $lead->status !== $request->status) {
                abort(response()->json([
                    'success' => false,
                    'message' => "Lead is in '{$lead->status}' stage and its status is permanently locked.",
                ], 422));
            }

            if ($request->has('deal_amount') && $request->deal_amount !== null) {
                $newDealAmount = (float) $request->deal_amount;
                $actualReceived = (float) \App\Models\CrmPayment::where('lead_id', $lead->id)->lockForUpdate()->sum('amount');
                if ($actualReceived > 0 && round($newDealAmount, 2) < round($actualReceived, 2)) {
                    $sym = $lead->currency_symbol;
                    abort(response()->json([
                        'success' => false,
                        'message' => "Deal amount cannot be reduced below the amount already received ({$sym}" . number_format($actualReceived, 2) . ").",
                    ], 422));
                }
                $lead->deal_amount = $newDealAmount;
            }

            $lead->status = $request->status;
            $lead->save();

            return $lead;
        });

        dispatch(new SyncLeadToGoogleSheetJob($lead->id, 'update_status'));

        // Refresh payments relation to compute updated amount_received, pending_payment, and payment_status
        $lead->load('payments');

        return response()->json([
            'success' => true,
            'message' => "Lead status updated to {$lead->status} successfully.",
            'lead' => [
                'id' => $lead->id,
                'status' => $lead->status,
                'deal_amount' => (float) $lead->deal_amount,
                'formatted_deal_amount' => $lead->formatted_deal_amount,
                'amount_received' => (float) $lead->amount_received,
                'pending_payment' => (float) $lead->pending_payment,
                'payment_status' => $lead->payment_status,
                'currency' => $lead->currency,
                'currency_symbol' => $lead->currency_symbol,
            ],
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $name = DB::transaction(function () use ($id) {
            // 2. Retrieve and lock the lead using CrmLead::withTrashed()->lockForUpdate()->findOrFail($id)
            $lead = CrmLead::withTrashed()->lockForUpdate()->findOrFail($id);

            // 3. Reject attempts to delete an already soft-deleted lead
            if ($lead->trashed()) {
                throw ValidationException::withMessages([
                    'lead' => ["This lead has already been deleted."],
                ]);
            }

            // 4. Check whether the lead has any payment records under lock
            $paymentsCount = CrmPayment::where('lead_id', $lead->id)->lockForUpdate()->count();
            if ($paymentsCount > 0) {
                throw ValidationException::withMessages([
                    'lead' => ["Cannot delete this lead because it has {$paymentsCount} recorded payment transaction(s). Financial transaction history must be preserved."],
                ]);
            }

            // 5. Check whether the lead status is Won
            if ($lead->status === 'Won') {
                throw ValidationException::withMessages([
                    'lead' => ["Cannot delete a Won deal. Use the appropriate business status or archival workflow instead."],
                ]);
            }

            // 7. Otherwise, execute normal Eloquent soft delete
            $businessName = $lead->business_name;
            $lead->delete();

            return $businessName;
        });

        // Sync with Google Sheets only if deletion succeeded
        dispatch(new SyncLeadToGoogleSheetJob($id, 'delete'));

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Lead '{$name}' deleted successfully.",
            ]);
        }

        return redirect()->route('admin.crm.leads.index')
            ->with('success', "Lead '{$name}' deleted successfully.");
    }
}
