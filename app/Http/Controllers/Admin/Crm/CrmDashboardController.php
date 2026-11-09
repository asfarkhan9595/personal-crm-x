<?php

namespace App\Http\Controllers\Admin\Crm;

use App\Http\Controllers\Controller;
use App\Models\CrmLead;
use App\Models\CrmPayment;
use App\Services\CrmFxRateService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CrmDashboardController extends Controller
{
    public function index(Request $request)
    {
        $fxService = app(CrmFxRateService::class);
        $aedRate = $fxService->getRate('AED');
        $usdRate = $fxService->getRate('USD');

        // All active leads with payments loaded
        $allLeads = CrmLead::with('payments')->orderBy('created_at', 'desc')->get();
        $wonLeads = $allLeads->where('status', 'Won')->values();

        // 1. TOP CARDS CALCULATIONS
        // Won contracts by currency
        $inWonTotal = (float) $wonLeads->where('currency', 'INR')->sum('deal_amount');
        $aedWonTotal = (float) $wonLeads->where('currency', 'AED')->sum('deal_amount');
        $usdWonTotal = (float) $wonLeads->where('currency', 'USD')->sum('deal_amount');
        $closedWonRevenueInr = $inWonTotal + ($aedWonTotal * $aedRate) + ($usdWonTotal * $usdRate);
        $wonDealRevenueInr = $closedWonRevenueInr;

        // Payments Ledger Total - exact sum of permanently locked received_inr from ALL payments across all leads
        $allPayments = CrmPayment::all();
        $amountReceivedInr = (float) $allPayments->sum('received_inr');

        // Native currency breakdown across ALL payments
        $inReceived = (float) $allPayments->where('currency', 'INR')->sum('amount');
        $aedReceived = (float) $allPayments->where('currency', 'AED')->sum('amount');
        $usdReceived = (float) $allPayments->where('currency', 'USD')->sum('amount');

        // Won Deal specific calculations
        $wonDealReceivedInr = 0.0;
        $inWonReceived = 0.0;
        $aedWonReceived = 0.0;
        $usdWonReceived = 0.0;
        $inPending = 0.0;
        $aedPending = 0.0;
        $usdPending = 0.0;
        foreach ($wonLeads as $wLead) {
            $wonDealReceivedInr += (float) $wLead->total_received_inr;
            if ($wLead->currency === 'INR') {
                $inWonReceived += $wLead->amount_received;
                $inPending += $wLead->pending_payment;
            } elseif ($wLead->currency === 'AED') {
                $aedWonReceived += $wLead->amount_received;
                $aedPending += $wLead->pending_payment;
            } elseif ($wLead->currency === 'USD') {
                $usdWonReceived += $wLead->amount_received;
                $usdPending += $wLead->pending_payment;
            }
        }
        $pendingPaymentInr = $inPending + ($aedPending * $aedRate) + ($usdPending * $usdRate);

        // Non-Won Payments received (e.g. Proposal / advance milestone payments)
        $nonWonPaymentReceivedInr = round(max(0.0, $amountReceivedInr - $wonDealReceivedInr), 2);

        // Average Won Deal Value
        $wonContractsCount = $wonLeads->count();
        $avgWonDealValueInr = $wonContractsCount > 0 ? round($closedWonRevenueInr / $wonContractsCount, 2) : 0.0;
        $inWonCount = $wonLeads->where('currency', 'INR')->count();
        $inAvgWon = $inWonCount > 0 ? round($inWonTotal / $inWonCount, 2) : 0.0;
        $aedWonCount = $wonLeads->where('currency', 'AED')->count();
        $aedAvgWon = $aedWonCount > 0 ? round($aedWonTotal / $aedWonCount, 2) : 0.0;
        $usdWonCount = $wonLeads->where('currency', 'USD')->count();
        $usdAvgWon = $usdWonCount > 0 ? round($usdWonTotal / $usdWonCount, 2) : 0.0;

        // 2. EARNINGS BY SERVICE (Automation, Website, Laravel)
        $servicesList = ['Automation', 'Website', 'Laravel'];
        $earningsByService = [];
        foreach ($servicesList as $svc) {
            $svcWonLeads = $wonLeads->where('service', $svc);
            $svcDealsCount = $svcWonLeads->count();
            
            $svcWonInr = 0.0;
            foreach ($svcWonLeads as $l) {
                $rate = $l->currency === 'AED' ? $aedRate : ($l->currency === 'USD' ? $usdRate : 1.0);
                $svcWonInr += ((float) $l->deal_amount * $rate);
            }

            // Pipeline in progress (New, Contacted, Proposal)
            $pipelineLeads = $allLeads->where('service', $svc)->whereIn('status', ['New', 'Contacted', 'Proposal']);
            $pipelineInr = 0.0;
            foreach ($pipelineLeads as $l) {
                $rate = $l->currency === 'AED' ? $aedRate : ($l->currency === 'USD' ? $usdRate : 1.0);
                $pipelineInr += ((float) $l->deal_amount * $rate);
            }

            $percentage = $closedWonRevenueInr > 0 ? round(($svcWonInr / $closedWonRevenueInr) * 100) : 0;

            $earningsByService[$svc] = [
                'service' => $svc,
                'deals_count' => $svcDealsCount,
                'won_inr' => $svcWonInr,
                'percentage' => $percentage,
                'pipeline_inr' => $pipelineInr,
            ];
        }

        // 3. EARNINGS BY MARKET: India (INR), Dubai (AED), USA (USD)
        $indiaWonLeads = $wonLeads->where('market', 'India');
        $indiaWonAmount = (float) $indiaWonLeads->sum('deal_amount');
        $indiaReceived = 0.0;
        $indiaOutstanding = 0.0;
        foreach ($indiaWonLeads as $l) {
            $indiaReceived += $l->amount_received;
            $indiaOutstanding += $l->pending_payment;
        }

        $dubaiWonLeads = $wonLeads->where('market', 'Dubai');
        $dubaiWonAmount = (float) $dubaiWonLeads->sum('deal_amount');
        $dubaiReceived = 0.0;
        $dubaiOutstanding = 0.0;
        foreach ($dubaiWonLeads as $l) {
            $dubaiReceived += $l->amount_received;
            $dubaiOutstanding += $l->pending_payment;
        }

        $usaWonLeads = $wonLeads->where('market', 'Other');
        $usaWonAmount = (float) $usaWonLeads->sum('deal_amount');
        $usaReceived = 0.0;
        $usaOutstanding = 0.0;
        foreach ($usaWonLeads as $l) {
            $usaReceived += $l->amount_received;
            $usaOutstanding += $l->pending_payment;
        }

        // Combined Pipeline Potential
        $pipelineInrTotal = (float) $allLeads->where('currency', 'INR')->whereIn('status', ['New', 'Contacted', 'Proposal'])->sum('deal_amount');
        $pipelineAedTotal = (float) $allLeads->where('currency', 'AED')->whereIn('status', ['New', 'Contacted', 'Proposal'])->sum('deal_amount');
        $pipelineUsdTotal = (float) $allLeads->where('currency', 'USD')->whereIn('status', ['New', 'Contacted', 'Proposal'])->sum('deal_amount');

        // Funnel counts for top widgets
        $totalLeadsCount = $allLeads->count();
        $contactedCount = $allLeads->where('status', '!=', 'New')->count();
        $interestedCount = $allLeads->where('response', 'Interested')->count();
        $totalPaymentsCount = CrmPayment::count();

        // 4. LEAD PIPELINE TABLE (Filtered)
        $pipelineFilter = $request->query('filter', 'all'); // all, won, pipeline, lost
        $marketFilter = $request->query('market');
        $serviceFilter = $request->query('service');
        $responseFilter = $request->query('response');
        $search = $request->query('search');

        $pipelineQuery = CrmLead::with('payments')->orderBy('id', 'asc');

        if ($pipelineFilter === 'won') {
            $pipelineQuery->where('status', 'Won');
        } elseif ($pipelineFilter === 'pipeline') {
            $pipelineQuery->whereIn('status', ['New', 'Contacted', 'Proposal']);
        } elseif ($pipelineFilter === 'lost') {
            $pipelineQuery->where('status', 'Lost');
        }

        if (!empty($marketFilter)) {
            $pipelineQuery->where('market', $marketFilter);
        }
        if (!empty($serviceFilter)) {
            $pipelineQuery->where('service', $serviceFilter);
        }
        if (!empty($responseFilter)) {
            $pipelineQuery->where('response', $responseFilter);
        }
        if (!empty($search)) {
            $pipelineQuery->search($search);
        }

        $pipelineLeads = $pipelineQuery->get();

        return view('admin.crm.dashboard', compact(
            'aedRate',
            'usdRate',
            'wonLeads',
            'closedWonRevenueInr',
            'wonDealRevenueInr',
            'wonContractsCount',
            'inWonTotal',
            'aedWonTotal',
            'usdWonTotal',
            'amountReceivedInr',
            'wonDealReceivedInr',
            'nonWonPaymentReceivedInr',
            'inReceived',
            'aedReceived',
            'usdReceived',
            'pendingPaymentInr',
            'inPending',
            'aedPending',
            'usdPending',
            'avgWonDealValueInr',
            'inAvgWon',
            'aedAvgWon',
            'usdAvgWon',
            'earningsByService',
            'indiaWonLeads',
            'indiaWonAmount',
            'indiaReceived',
            'indiaOutstanding',
            'dubaiWonLeads',
            'dubaiWonAmount',
            'dubaiReceived',
            'dubaiOutstanding',
            'usaWonLeads',
            'usaWonAmount',
            'usaReceived',
            'usaOutstanding',
            'pipelineInrTotal',
            'pipelineAedTotal',
            'pipelineUsdTotal',
            'totalLeadsCount',
            'contactedCount',
            'interestedCount',
            'totalPaymentsCount',
            'pipelineLeads',
            'pipelineFilter',
            'marketFilter',
            'serviceFilter',
            'responseFilter',
            'search',
            'allLeads'
        ));
    }
}
