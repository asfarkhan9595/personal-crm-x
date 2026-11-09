@extends('layouts.admin')

@section('title', 'Lead Tracker & Closed Won Earnings')

@section('content')
<div class="space-y-6">
    <!-- Top Header Bar matching Google Sheet Canvas Reference -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Live Sheets Connected
                </span>
                <span class="text-xs text-gray-400 font-medium">Zero-Guessing Earnings Engine</span>
            </div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Lead Tracker & Closed Won Earnings</h1>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <!-- FX Rates Live Pills -->
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 text-xs font-bold text-indigo-700 dark:text-indigo-300">
                <i data-lucide="coins" class="w-3.5 h-3.5 text-indigo-500"></i>
                <span>1 AED = ₹{{ round($aedRate) }}</span>
            </div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 text-xs font-bold text-emerald-700 dark:text-emerald-300">
                <i data-lucide="dollar-sign" class="w-3.5 h-3.5 text-emerald-500"></i>
                <span>$ 1 USD = ₹{{ round($usdRate) }}</span>
            </div>

            <!-- Action Buttons -->
            <button onclick="openGlobalPaymentModal()" type="button" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm shadow-emerald-500/20">
                <i data-lucide="receipt" class="w-4 h-4 mr-1.5"></i> + Add Payment
            </button>
            <button onclick="openLeadModal()" type="button" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm shadow-indigo-500/20">
                <i data-lucide="plus" class="w-4 h-4 mr-1.5"></i> + Add Lead / Deal
            </button>
        </div>
    </div>

    <!-- Navigation Tab Switcher -->
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.crm.dashboard') }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-indigo-600 text-white shadow-sm flex items-center gap-2">
            <i data-lucide="layout-grid" class="w-4 h-4"></i> Dashboard & Table
        </a>
        <a href="{{ route('admin.crm.leads.index', ['view' => 'kanban']) }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700 flex items-center gap-2 transition-colors">
            <i data-lucide="kanban" class="w-4 h-4 text-gray-400"></i> Kanban Board
        </a>
        <a href="{{ route('admin.crm.payments.index') }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700 flex items-center gap-2 transition-colors">
            <i data-lucide="receipt" class="w-4 h-4 text-gray-400"></i> Payments Ledger ({{ $totalPaymentsCount }})
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
            <span class="text-xs font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/20">
            <div class="flex items-center gap-2 font-bold text-xs mb-1">
                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                <span>Please fix the following validation errors:</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-0.5 ml-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Top 4 Metric Cards matching Design Reference -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Closed Won Revenue (Green Banner Card) -->
        <div class="bg-gradient-to-br from-emerald-700 via-teal-700 to-emerald-800 rounded-2xl p-5 text-white shadow-md relative overflow-hidden flex flex-col justify-between">
            <div class="absolute -right-4 -bottom-4 text-white/10">
                <i data-lucide="indian-rupee" class="w-32 h-32"></i>
            </div>
            <div>
                <div class="flex items-center justify-between text-emerald-100 text-[11px] font-bold uppercase tracking-wider">
                    <span>Closed Won Revenue</span>
                    <span class="bg-white/20 px-2 py-0.5 rounded text-[10px]">{{ $wonContractsCount }} Contracts Won</span>
                </div>
                <div class="text-3xl font-black mt-2 tracking-tight">₹{{ number_format($closedWonRevenueInr, 0) }}</div>
            </div>
            <div class="mt-4 pt-3 border-t border-white/15 flex items-center justify-between text-xs font-medium text-emerald-100">
                <span>IN: ₹{{ number_format($inWonTotal, 0) }}</span>
                <span>AE: AED {{ number_format($aedWonTotal, 0) }}</span>
                @if($usdWonTotal > 0)
                    <span>US: ${{ number_format($usdWonTotal, 0) }}</span>
                @endif
            </div>
        </div>

        <!-- Card 2: Amount Received -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Amount Received</span>
                <div class="w-7 h-7 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                </div>
            </div>
            <div>
                <div class="text-3xl font-black text-gray-900 dark:text-white mt-2 tracking-tight">
                    ₹{{ number_format($amountReceivedInr, 0) }}
                </div>
                @if($nonWonPaymentReceivedInr > 0)
                    <div class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 mt-0.5 flex items-center gap-1.5">
                        <span>Won: ₹{{ number_format($wonDealReceivedInr, 0) }}</span>
                        <span class="text-gray-300 dark:text-gray-600">•</span>
                        <span>Pipeline: ₹{{ number_format($nonWonPaymentReceivedInr, 0) }}</span>
                    </div>
                @endif
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between text-xs font-medium text-gray-500 dark:text-gray-400">
                <span>IN: ₹{{ number_format($inReceived, 0) }}</span>
                <span class="text-emerald-600 dark:text-emerald-400 font-bold">AE: AED {{ number_format($aedReceived, 0) }}</span>
                @if($usdReceived > 0)
                    <span class="text-indigo-600 dark:text-indigo-400 font-bold">US: ${{ number_format($usdReceived, 0) }}</span>
                @endif
            </div>
        </div>

        <!-- Card 3: Pending Payment (Orange Accent) -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">Pending Payment</span>
                <div class="w-7 h-7 rounded-full bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
            </div>
            <div>
                <div class="text-3xl font-black text-amber-500 mt-2 tracking-tight">
                    ₹{{ number_format($pendingPaymentInr, 0) }}
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between text-xs font-medium text-gray-500 dark:text-gray-400">
                <span>IN: ₹{{ number_format($inPending, 0) }}</span>
                <span>AE: AED {{ number_format($aedPending, 0) }}</span>
                @if($usdPending > 0)
                    <span>US: ${{ number_format($usdPending, 0) }}</span>
                @endif
            </div>
        </div>

        <!-- Card 4: Avg Won Deal Value -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Avg Won Deal Value</span>
                <div class="w-7 h-7 rounded-full bg-indigo-100 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-4 h-4"></i>
                </div>
            </div>
            <div>
                <div class="text-3xl font-black text-gray-900 dark:text-white mt-2 tracking-tight">
                    ₹{{ number_format($avgWonDealValueInr, 0) }}
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between text-xs font-medium text-gray-500 dark:text-gray-400">
                <span>IN Avg: ₹{{ number_format($inAvgWon, 0) }}</span>
                <span>AE: AED {{ number_format($aedAvgWon, 0) }}</span>
                @if(($usdWonCount ?? 0) > 0)
                    <span>US: ${{ number_format($usdAvgWon, 0) }}</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Middle Section: Earnings by Service & Earnings by Market -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Earnings by Service Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                            <i data-lucide="briefcase" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white text-sm">Earnings by Service</h3>
                            <p class="text-[11px] text-gray-400">Won revenue from backing sheet contracts</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300">
                        ₹{{ number_format($closedWonRevenueInr, 0) }} Won
                    </span>
                </div>

                <div class="mt-5 space-y-5">
                    @foreach($earningsByService as $svc)
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1.5">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300">
                                        {{ $svc['service'] }}
                                    </span>
                                    <span class="text-gray-400 text-[11px]">{{ $svc['deals_count'] }} deal(s) won</span>
                                </div>
                                <span class="font-black text-gray-900 dark:text-white">
                                    ₹{{ number_format($svc['won_inr'], 0) }} ({{ $svc['percentage'] }}%)
                                </span>
                            </div>
                            <!-- Progress Bar -->
                            <div class="w-full h-2.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-indigo-500 to-emerald-500 rounded-full" style="width: {{ max(4, $svc['percentage']) }}%"></div>
                            </div>
                            <div class="mt-1 text-[11px] text-gray-400">
                                Pipeline in progress: ₹{{ number_format($svc['pipeline_inr'], 0) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Earnings by Market Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center">
                            <i data-lucide="globe" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white text-sm">Earnings by Market</h3>
                            <p class="text-[11px] text-gray-400">India (INR ₹), Dubai (AED) & Global (USD $)</p>
                        </div>
                    </div>
                    <span class="text-[11px] text-gray-400 font-mono">
                        AED: ₹{{ round($aedRate) }} | USD: ₹{{ round($usdRate) }}
                    </span>
                </div>

                <!-- 3 Market Sub-Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-4">
                    <!-- INDIA (INR) -->
                    <div class="p-3.5 rounded-xl border border-orange-200 dark:border-orange-500/20 bg-orange-50/30 dark:bg-orange-500/5 relative overflow-hidden">
                        <div class="h-1 bg-orange-500 absolute top-0 left-0 right-0"></div>
                        <div class="flex justify-between items-center text-[10px] uppercase font-bold text-orange-600 dark:text-orange-400 mb-1">
                            <span>INDIA (INR)</span>
                            <span class="text-gray-400 font-normal">{{ $indiaWonLeads->count() }} deals</span>
                        </div>
                        <div class="text-lg font-black text-gray-900 dark:text-white">
                            ₹{{ number_format($indiaWonAmount, 0) }}
                        </div>
                        <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-2 space-y-0.5">
                            <div>Received: ₹{{ number_format($indiaReceived, 0) }}</div>
                            <div class="text-orange-600 dark:text-orange-400 font-semibold">Outstanding: ₹{{ number_format($indiaOutstanding, 0) }}</div>
                        </div>
                    </div>

                    <!-- DUBAI (AED) -->
                    <div class="p-3.5 rounded-xl border border-cyan-200 dark:border-cyan-500/20 bg-cyan-50/30 dark:bg-cyan-500/5 relative overflow-hidden">
                        <div class="h-1 bg-cyan-500 absolute top-0 left-0 right-0"></div>
                        <div class="flex justify-between items-center text-[10px] uppercase font-bold text-cyan-600 dark:text-cyan-400 mb-1">
                            <span>DUBAI (AED)</span>
                            <span class="text-gray-400 font-normal">{{ $dubaiWonLeads->count() }} deal</span>
                        </div>
                        <div class="text-lg font-black text-gray-900 dark:text-white">
                            AED {{ number_format($dubaiWonAmount, 0) }}
                        </div>
                        <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-2 space-y-0.5">
                            <div>Received: AED {{ number_format($dubaiReceived, 0) }}</div>
                            <div class="text-cyan-600 dark:text-cyan-400 font-medium">INR Equiv: ~₹{{ number_format($dubaiWonAmount * $aedRate, 0) }}</div>
                        </div>
                    </div>

                    <!-- USA (USD) -->
                    <div class="p-3.5 rounded-xl border border-emerald-200 dark:border-emerald-500/20 bg-emerald-50/30 dark:bg-emerald-500/5 relative overflow-hidden">
                        <div class="h-1 bg-emerald-500 absolute top-0 left-0 right-0"></div>
                        <div class="flex justify-between items-center text-[10px] uppercase font-bold text-emerald-600 dark:text-emerald-400 mb-1">
                            <span>USA (USD)</span>
                            <span class="text-gray-400 font-normal">{{ $usaWonLeads->count() }} deals</span>
                        </div>
                        <div class="text-lg font-black text-gray-900 dark:text-white">
                            ${{ number_format($usaWonAmount, 0) }}
                        </div>
                        <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-2 space-y-0.5">
                            <div>Received: ${{ number_format($usaReceived, 0) }}</div>
                            <div class="text-emerald-600 dark:text-emerald-400 font-medium">INR Equiv: ~₹{{ number_format($usaWonAmount * $usdRate, 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Combined Pipeline Potential Footer -->
            <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between text-xs">
                <span class="text-gray-400">Combined Pipeline Potential:</span>
                <span class="font-bold text-indigo-600 dark:text-indigo-400">
                    ₹{{ number_format($pipelineInrTotal, 0) }} + AED {{ number_format($pipelineAedTotal, 0) }}@if($pipelineUsdTotal > 0) + ${{ number_format($pipelineUsdTotal, 0) }}@endif
                </span>
            </div>
        </div>
    </div>

    <!-- Closed Won Deals Breakdown Section matching Image 2 -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-base">Closed Won Deals Breakdown</h3>
                    <p class="text-xs text-gray-400">Every deal that directly forms your ₹{{ number_format($closedWonRevenueInr, 0) }} won total</p>
                </div>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/20">
                Won Total: ₹{{ number_format($closedWonRevenueInr, 0) }} | ${{ number_format($closedWonRevenueInr / ($usdRate ?: 84), 0) }} USD
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pt-2">
            @forelse($wonLeads as $wLead)
                <div class="p-5 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/80 shadow-sm flex flex-col justify-between">
                    <div>
                        <!-- Header -->
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <h4 class="font-bold text-sm text-gray-900 dark:text-white">{{ $wLead->business_name }}</h4>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $wLead->payment_status === 'Paid' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-300' }}">
                                {{ $wLead->payment_status }}
                            </span>
                        </div>

                        <!-- Badges -->
                        <div class="flex items-center gap-1.5 mb-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                                {{ $wLead->market }}
                            </span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-300">
                                {{ $wLead->service }}
                            </span>
                        </div>

                        <!-- Financials Box -->
                        <div class="space-y-1.5 text-xs">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Deal Amount:</span>
                                <span class="font-bold text-gray-900 dark:text-white">{{ $wLead->formatted_deal_amount }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Amount Received:</span>
                                <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $wLead->currency_symbol }}{{ number_format($wLead->amount_received, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Pending Payment:</span>
                                <span class="font-bold text-amber-500">{{ $wLead->currency_symbol }}{{ number_format($wLead->pending_payment, 2) }}</span>
                            </div>
                            @if($wLead->currency !== 'INR')
                                <div class="text-[10px] text-gray-400 text-right pt-0.5">
                                    Converted: ₹{{ number_format($wLead->deal_amount * ($wLead->currency === 'AED' ? $aedRate : $usdRate), 0) }} (@ ₹{{ round($wLead->currency === 'AED' ? $aedRate : $usdRate) }}/{{ $wLead->currency }})
                                </div>
                            @endif
                        </div>

                        <!-- Outreach Status Tags -->
                        <div class="mt-4 pt-3 border-t border-gray-200/60 dark:border-gray-700/60 flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-300">
                                Sent: {{ $wLead->message_sent ? 'Yes' : 'No' }}
                            </span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                                {{ $wLead->response }}
                            </span>
                        </div>

                        @if($wLead->problem_found)
                            <div class="mt-2 text-[11px] italic text-gray-500 dark:text-gray-400">
                                "{{ $wLead->problem_found }}"
                            </div>
                        @endif
                    </div>

                    <!-- Card Actions -->
                    <div class="mt-5 pt-3 border-t border-gray-200/60 dark:border-gray-700/60 flex items-center justify-between">
                        <button onclick="openLeadPaymentModal({{ $wLead->id }}, '{{ addslashes($wLead->business_name) }}', '{{ $wLead->currency }}', {{ $wLead->deal_amount }}, {{ $wLead->amount_received }}, {{ $wLead->pending_payment }})" type="button" class="inline-flex items-center px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                            <i data-lucide="plus" class="w-3.5 h-3.5 mr-1"></i> Add Payment
                        </button>
                        <a href="{{ route('admin.crm.leads.show', $wLead->id) }}" class="text-xs text-gray-400 hover:text-indigo-600 flex items-center gap-1">
                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-8 text-gray-400 text-xs">
                    No closed won deals recorded yet.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Bottom Section: Lead Pipeline & Deal Values Table matching Image 3 -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6 pb-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-base">
                        Lead Pipeline & Deal Values <span class="text-xs text-gray-400 font-normal">({{ $pipelineLeads->count() }} leads)</span>
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Directly edit deal values, switch currencies (USD/INR), adjust outreach, and manage status live.
                    </p>
                </div>

                <!-- Filter Buttons Row -->
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ request()->fullUrlWithQuery(['filter' => 'all']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $pipelineFilter === 'all' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                        All
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['filter' => 'won']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $pipelineFilter === 'won' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                        Won Only
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['filter' => 'pipeline']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $pipelineFilter === 'pipeline' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                        Pipeline
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['filter' => 'lost']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $pipelineFilter === 'lost' ? 'bg-red-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                        Lost
                    </a>
                </div>
            </div>

            <!-- Search and Dropdown Filter Row -->
            <form method="GET" action="{{ route('admin.crm.dashboard') }}" class="flex flex-wrap items-center gap-3 mt-4 text-xs">
                <input type="hidden" name="filter" value="{{ $pipelineFilter }}">
                <div class="flex-1 min-w-[200px] relative">
                    <i data-lucide="search" class="w-3.5 h-3.5 absolute left-3 top-2.5 text-gray-400"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search business, problem, or site..." class="w-full pl-9 pr-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-xs">
                </div>

                <select name="market" onchange="this.form.submit()" class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                    <option value="">All Markets</option>
                    <option value="India" {{ $marketFilter === 'India' ? 'selected' : '' }}>India</option>
                    <option value="Dubai" {{ $marketFilter === 'Dubai' ? 'selected' : '' }}>Dubai</option>
                    <option value="Other" {{ $marketFilter === 'Other' ? 'selected' : '' }}>Other</option>
                </select>

                <select name="service" onchange="this.form.submit()" class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                    <option value="">All Services</option>
                    <option value="Website" {{ $serviceFilter === 'Website' ? 'selected' : '' }}>Website</option>
                    <option value="Laravel" {{ $serviceFilter === 'Laravel' ? 'selected' : '' }}>Laravel</option>
                    <option value="Automation" {{ $serviceFilter === 'Automation' ? 'selected' : '' }}>Automation</option>
                </select>

                <select name="response" onchange="this.form.submit()" class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                    <option value="">All Responses</option>
                    <option value="Interested" {{ $responseFilter === 'Interested' ? 'selected' : '' }}>Interested</option>
                    <option value="No response" {{ $responseFilter === 'No response' ? 'selected' : '' }}>No response</option>
                    <option value="Not interested" {{ $responseFilter === 'Not interested' ? 'selected' : '' }}>Not interested</option>
                </select>
            </form>
        </div>

        <!-- Dark Navy Header Table matching Design Reference Image 3 -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[#0f172a] text-gray-300 uppercase text-[10px] tracking-wider font-bold">
                        <th class="px-4 py-3.5 whitespace-nowrap">Business Name</th>
                        <th class="px-3 py-3.5 whitespace-nowrap">Market</th>
                        <th class="px-3 py-3.5 whitespace-nowrap">Service</th>
                        <th class="px-3 py-3.5 whitespace-nowrap">Status</th>
                        <th class="px-3 py-3.5 whitespace-nowrap">Currency</th>
                        <th class="px-3 py-3.5 whitespace-nowrap">Deal Amount</th>
                        <th class="px-3 py-3.5 whitespace-nowrap">Amount Received</th>
                        <th class="px-3 py-3.5 whitespace-nowrap">Pending Payment</th>
                        <th class="px-3 py-3.5 whitespace-nowrap">Payment Status</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Converted INR</th>
                        <th class="px-3 py-3.5 whitespace-nowrap">Contact</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Outreach</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-800 dark:text-gray-200">
                    @forelse($pipelineLeads as $lead)
                        @php
                            $rate = $lead->currency === 'AED' ? $aedRate : ($lead->currency === 'USD' ? $usdRate : 1.0);
                            $convertedInr = (float) $lead->deal_amount * $rate;
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <!-- Business Name -->
                            <td class="px-4 py-3.5">
                                <a href="{{ route('admin.crm.leads.show', $lead->id) }}" class="font-bold text-gray-900 dark:text-white hover:text-emerald-600 block line-clamp-1">
                                    {{ $lead->business_name }}
                                </a>
                                @if($lead->website_url)
                                    <a href="{{ $lead->website_url }}" target="_blank" class="text-[10px] text-gray-400 hover:text-indigo-500 truncate block max-w-[140px]">
                                        {{ $lead->website_url }}
                                    </a>
                                @endif
                            </td>

                            <!-- Market -->
                            <td class="px-3 py-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                                    {{ $lead->market }}
                                </span>
                            </td>

                            <!-- Service -->
                            <td class="px-3 py-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-300">
                                    {{ $lead->service }}
                                </span>
                            </td>

                            <!-- Status Dropdown (Live AJAX Update with Won Confirmation Modal) -->
                            <td class="px-3 py-3.5">
                                <select 
                                    data-previous-status="{{ $lead->status }}"
                                    onfocus="this.dataset.previousStatus = this.value"
                                    onchange="handleLeadStatusChange({{ $lead->id }}, this.value, {{ (float)$lead->deal_amount }}, {{ (float)$lead->amount_received }}, {{ (float)$lead->pending_payment }}, '{{ addslashes($lead->business_name) }}', '{{ $lead->currency }}', '{{ $lead->currency_symbol }}', this)" 
                                    @if(in_array($lead->status, ['Won', 'Lost'])) disabled title="{{ $lead->status }} status is permanently locked" @endif
                                    class="text-[11px] font-bold px-2.5 py-1 rounded-full border-0 shadow-sm
                                    {{ in_array($lead->status, ['Won', 'Lost']) ? 'opacity-70 cursor-not-allowed' : 'cursor-pointer' }}
                                    {{ $lead->status === 'Won' ? 'bg-emerald-600 text-white' : 
                                       ($lead->status === 'Lost' ? 'bg-red-600 text-white' : 
                                       ($lead->status === 'Proposal' ? 'bg-purple-600 text-white' : 'bg-blue-600 text-white')) }}">
                                    <option value="New" {{ $lead->status === 'New' ? 'selected' : '' }}>New</option>
                                    <option value="Contacted" {{ $lead->status === 'Contacted' ? 'selected' : '' }}>Contacted</option>
                                    <option value="Proposal" {{ $lead->status === 'Proposal' ? 'selected' : '' }}>Proposal</option>
                                    <option value="Won" {{ $lead->status === 'Won' ? 'selected' : '' }}>Won (Closed)</option>
                                    <option value="Lost" {{ $lead->status === 'Lost' ? 'selected' : '' }}>Lost</option>
                                </select>
                            </td>

                            <!-- Currency -->
                            <td class="px-3 py-3.5 font-bold font-mono">
                                {{ $lead->currency === 'INR' ? 'INR (₹)' : $lead->currency }}
                            </td>

                            <!-- Deal Amount -->
                            <td class="px-3 py-3.5">
                                <span class="font-bold text-gray-900 dark:text-white px-2 py-1 rounded bg-gray-50 dark:bg-gray-700/50">
                                    {{ number_format($lead->deal_amount, 0) }}
                                </span>
                            </td>

                            <!-- Amount Received -->
                            <td class="px-3 py-3.5 font-semibold text-emerald-600 dark:text-emerald-400">
                                {{ number_format($lead->amount_received, 0) }}
                            </td>

                            <!-- Pending Payment -->
                            <td class="px-3 py-3.5">
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold {{ $lead->pending_payment > 0 ? 'bg-amber-100 text-amber-900 dark:bg-amber-500/20 dark:text-amber-300' : 'text-gray-400' }}">
                                    {{ $lead->currency_symbol }}{{ number_format($lead->pending_payment, 0) }}
                                </span>
                            </td>

                            <!-- Payment Status Badge -->
                            <td class="px-3 py-3.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold 
                                    {{ $lead->payment_status === 'Paid' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300' : 
                                       ($lead->payment_status === 'Partial' ? 'bg-blue-100 text-blue-800 dark:bg-blue-500/20 dark:text-blue-300' : 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-300') }}">
                                    {{ $lead->payment_status }}
                                </span>
                            </td>

                            <!-- Converted INR -->
                            <td class="px-4 py-3.5 font-bold text-gray-900 dark:text-white whitespace-nowrap">
                                ₹{{ number_format($convertedInr, 0) }}
                                @if($lead->currency !== 'INR')
                                    <span class="block text-[9px] text-gray-400 font-normal">({{ $lead->currency }} {{ number_format($lead->deal_amount, 0) }} &times; {{ round($rate) }})</span>
                                @endif
                            </td>

                            <!-- Contact Channel -->
                            <td class="px-3 py-3.5">
                                <span class="inline-flex items-center gap-1 text-[11px] font-medium text-emerald-600 dark:text-emerald-400">
                                    <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                                    {{ $lead->contact_channel }}
                                </span>
                            </td>

                            <!-- Outreach Badges -->
                            <td class="px-4 py-3.5 space-y-0.5 whitespace-nowrap">
                                <div class="text-[10px] font-medium {{ $lead->message_sent ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400' }}">
                                    Sent: {{ $lead->message_sent ? 'Yes' : 'No' }}
                                </div>
                                <div class="text-[10px] font-medium {{ $lead->response === 'Interested' ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400' }}">
                                    {{ $lead->response }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center py-8 text-gray-400 text-xs">
                                No leads match your filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Global Add Payment Modal matching Google Sheet Canvas Requirements -->
<div id="globalPaymentModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
    <div onclick="closeGlobalPaymentModal()" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="relative z-10 w-full max-w-lg bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700 mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <i data-lucide="receipt" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Record Client Payment</h3>
                    <p class="text-xs text-gray-400">Locked transaction exchange rate</p>
                </div>
            </div>
            <button onclick="closeGlobalPaymentModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="globalPaymentForm" method="POST" class="space-y-4">
            @csrf
            <!-- Client / Business Dropdown -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                    Select Client / Business *
                </label>
                <select id="paymentLeadSelect" onchange="onPaymentLeadChange()" required class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white font-medium">
                    <option value="">-- Choose Client --</option>
                    @foreach($wonLeads as $l)
                        @php
                            $leadFxRate = $l->currency === 'AED' ? (float)$aedRate : ($l->currency === 'USD' ? (float)$usdRate : 1.0000);
                        @endphp
                        <option value="{{ $l->id }}" 
                                data-deal="{{ (float) $l->deal_amount }}"
                                data-received="{{ (float) $l->amount_received }}"
                                data-pending="{{ (float) $l->pending_payment }}"
                                data-currency="{{ $l->currency }}"
                                data-symbol="{{ $l->currency_symbol }}"
                                data-fx-rate="{{ $leadFxRate }}">
                            {{ $l->business_name }} ({{ $l->currency }} &bull; Deal: {{ $l->currency_symbol }}{{ number_format($l->deal_amount, 0) }} &bull; Pending: {{ $l->currency_symbol }}{{ number_format($l->pending_payment, 0) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Client Financial Summary Bar -->
            <div id="leadFinancialSummaryBar" class="p-3 rounded-xl bg-gray-50 dark:bg-gray-700/40 border border-gray-200 dark:border-gray-700 flex justify-between items-center text-xs hidden">
                <div>
                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Deal Value:</span>
                    <span id="summaryDeal" class="font-bold text-gray-900 dark:text-white">-</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Received So Far:</span>
                    <span id="summaryReceived" class="font-bold text-emerald-600">-</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Pending Balance:</span>
                    <span id="summaryPending" class="font-bold text-amber-500">-</span>
                </div>
            </div>

            <!-- Payment Amount Field (Only required field) -->
            <div>
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                        Payment Amount *
                    </label>
                    <!-- Quick Shortcut Buttons -->
                    <div class="flex items-center gap-1.5">
                        <button type="button" onclick="fillShortcutPayment('full_pending')" class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-100 hover:bg-emerald-200 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300">
                            Pay Full Pending
                        </button>
                        <button type="button" onclick="fillShortcutPayment('50_deal')" class="text-[10px] font-bold px-2 py-0.5 rounded bg-indigo-100 hover:bg-indigo-200 text-indigo-800 dark:bg-indigo-500/20 dark:text-indigo-300">
                            50%
                        </button>
                    </div>
                </div>
                <div class="relative">
                    <span id="payCurrencyPrefix" class="absolute left-3 top-2.5 font-bold text-gray-500 text-xs">₹</span>
                    <input type="number" step="0.01" min="0.01" name="amount" id="globalPayAmount" required placeholder="Enter payment amount" oninput="recalcGlobalInr()" class="w-full text-sm font-bold pl-10 pr-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <!-- Realized INR Live Preview Box -->
            <div class="p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-xs font-medium text-emerald-800 dark:text-emerald-300 block">Realized Cash in Bank:</span>
                        <span class="text-[10px] text-emerald-600/80">FX rate permanently locked on save</span>
                    </div>
                    <span id="globalPreviewInr" class="text-lg font-black text-emerald-700 dark:text-emerald-400">₹0.00</span>
                </div>
            </div>

            <!-- Collapsible: Edit Date or Exchange Rate -->
            <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-3 bg-gray-50/50 dark:bg-gray-700/20">
                <button type="button" onclick="toggleFxCollapse()" class="w-full flex items-center justify-between text-xs font-semibold text-gray-600 dark:text-gray-300">
                    <span class="flex items-center gap-1.5">
                        <i data-lucide="sliders" class="w-3.5 h-3.5 text-gray-400"></i> Edit Date or Exchange Rate (Optional)
                    </span>
                    <i id="fxChevron" data-lucide="chevron-down" class="w-4 h-4 transition-transform"></i>
                </button>

                <div id="fxCollapseContent" class="space-y-3 pt-3 hidden">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Date Paid</label>
                            <input type="date" name="paid_on" id="globalPayDate" value="{{ date('Y-m-d') }}" class="w-full text-xs px-2.5 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">FX Rate to INR</label>
                            <input type="number" step="0.0001" min="0.0001" name="fx_rate" id="globalPayFxRate" value="1.0000" oninput="recalcGlobalInr()" class="w-full text-xs font-mono px-2.5 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Payment Note</label>
                        <input type="text" name="note" placeholder="Milestone / Payment notes..." class="w-full text-xs px-2.5 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t border-gray-200 dark:border-gray-700">
                <button onclick="closeGlobalPaymentModal()" type="button" class="px-4 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                    Cancel
                </button>
                <button type="submit" id="globalPaymentSubmitBtn" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm flex items-center justify-center">
                    Lock & Save Payment
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add Lead Modal matching Image 5 -->
<div id="addLeadModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
    <div onclick="closeLeadModal()" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="relative z-10 w-full max-w-lg bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700 mb-4">
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Add New Lead</h3>
                <p class="text-xs text-gray-400">Capture lead for outreach pipeline</p>
            </div>
            <button onclick="closeLeadModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('admin.crm.leads.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Business Name *</label>
                <input type="text" name="business_name" required placeholder="e.g. Apex Logistics Hub" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Market</label>
                    <select name="market" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="India">India</option>
                        <option value="Dubai">Dubai</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Service</label>
                    <select name="service" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="Website">Website</option>
                        <option value="Laravel">Laravel</option>
                        <option value="Automation">Automation</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Channel</label>
                    <select name="contact_channel" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="WhatsApp">WhatsApp</option>
                        <option value="Instagram">Instagram</option>
                        <option value="Email">Email</option>
                        <option value="Call">Call</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Website URL</label>
                    <input type="url" name="website_url" placeholder="https://example.com" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Problem Found / Need</label>
                <textarea name="problem_found" rows="2" placeholder="e.g. Broken checkout page, manual order entry spreadsheet" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Currency</label>
                    <select name="currency" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white font-bold">
                        <option value="INR">INR (₹)</option>
                        <option value="AED">AED</option>
                        <option value="USD">USD ($)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Deal Amount</label>
                    <input type="number" step="0.01" min="0" name="deal_amount" placeholder="Deal Amount" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Follow-up Date</label>
                    <input type="date" name="follow_up_date" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Notes (Optional)</label>
                    <input type="text" name="notes" placeholder="Initial client requirements..." class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="flex items-center pt-3">
                    <label class="inline-flex items-center text-xs font-medium text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="message_sent" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mr-2">
                        Outreach Message Sent
                    </label>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Lead Response</label>
                    <select name="response" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="No response">No response</option>
                        <option value="Interested">Interested</option>
                        <option value="Not interested">Not interested</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Pipeline Stage</label>
                <select name="status" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="New" selected>New Lead</option>
                    <option value="Contacted">Contacted</option>
                    <option value="Proposal">Proposal</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                <button onclick="closeLeadModal()" type="button" class="px-4 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm">
                    Create Lead
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Won Confirmation Modal matching decoupled architecture -->
<div id="wonConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
    <div onclick="closeWonModal()" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="relative z-10 w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                <i data-lucide="award" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Mark Deal as Won (Closed)</h3>
                <p id="wonModalBizName" class="text-xs text-gray-400 font-semibold"></p>
            </div>
        </div>

        <p class="text-xs text-gray-600 dark:text-gray-300 mb-4">
            Confirm the contract terms to close this deal. Changing status to Won locks commercial terms and does not automatically record payments.
        </p>

        <!-- Financial Balance Overview -->
        <div class="p-3.5 rounded-xl bg-gray-50 dark:bg-gray-700/40 border border-gray-200 dark:border-gray-700 mb-4 space-y-2.5 text-xs">
            <!-- 1. Deal Amount (editable) -->
            <div>
                <div class="flex justify-between items-center mb-1">
                    <label class="font-semibold text-gray-700 dark:text-gray-300">
                        Contract Deal Value (<span id="wonCurrencyLabel">INR</span>)
                    </label>
                    <span id="wonStatusPreviewBadge" class="px-2 py-0.5 rounded-full font-bold text-[10px] bg-gray-100 text-gray-700">
                        Pending
                    </span>
                </div>
                <div class="relative">
                    <span id="wonCurrencyPrefix" class="absolute left-3 top-2 font-bold text-gray-500 text-xs">₹</span>
                    <input type="number" step="0.01" min="0" id="wonDealAmountInput" oninput="recalcWonStatus()" class="w-full text-sm font-bold pl-9 pr-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div id="wonDealAmountError" class="text-xs text-red-500 mt-1 hidden font-medium"></div>
            </div>

            <!-- Ledger Summary Grid -->
            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-gray-200 dark:border-gray-600">
                <div class="p-2 rounded-lg bg-white dark:bg-gray-800/80 border border-gray-200/70 dark:border-gray-700">
                    <span class="text-[10px] text-gray-400 block font-semibold uppercase">Total Received</span>
                    <span id="wonModalReceivedAmount" class="text-xs font-bold text-emerald-600 dark:text-emerald-400">₹0.00</span>
                </div>
                <div class="p-2 rounded-lg bg-white dark:bg-gray-800/80 border border-gray-200/70 dark:border-gray-700">
                    <span class="text-[10px] text-gray-400 block font-semibold uppercase">Remaining Pending</span>
                    <span id="wonModalPendingAmount" class="text-xs font-bold text-amber-500">₹0.00</span>
                </div>
            </div>
        </div>

        <!-- Fully Settled Notice (shown when pending <= 0) -->
        <div id="wonFullyPaidNotice" class="mb-5 p-3 rounded-xl border border-emerald-200 dark:border-emerald-500/20 bg-emerald-50/50 dark:bg-emerald-500/10 text-xs text-emerald-700 dark:text-emerald-300 flex items-center gap-2 hidden">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0"></i>
            <span>Deal is 100% paid and settled. No further payments required.</span>
        </div>

        <!-- Optional Separate Payment Action Section (hidden when pending <= 0) -->
        <div id="wonOptionalPaymentSection" class="mb-5 p-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
            <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-gray-800 dark:text-gray-200">
                <input type="checkbox" id="wonRecordPaymentCheckbox" onchange="toggleWonPaymentFields()" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                <span>Also record an upfront / advance payment received now</span>
            </label>

            <div id="wonPaymentFields" class="mt-3 space-y-3 hidden pt-3 border-t border-gray-200 dark:border-gray-700">
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="text-[11px] font-semibold text-gray-600 dark:text-gray-400">Amount Received Today *</label>
                        <span id="wonMaxPayableNotice" class="text-[10px] text-gray-400"></span>
                    </div>
                    <div class="relative">
                        <span id="wonReceivedCurrencyPrefix" class="absolute left-3 top-2 font-bold text-gray-500 text-xs">₹</span>
                        <input type="number" step="0.01" min="0.01" id="wonAmountReceivedInput" placeholder="0.00" oninput="recalcWonPaymentPreview()" class="w-full text-xs font-bold pl-9 pr-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div id="wonPaymentError" class="text-xs text-red-500 mt-1 hidden font-medium"></div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-[11px] font-semibold text-gray-600 dark:text-gray-400 block mb-1">Payment Date</label>
                        <input type="date" id="wonPaymentDateInput" value="{{ date('Y-m-d') }}" class="w-full text-xs py-1.5 px-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="text-[11px] font-semibold text-gray-600 dark:text-gray-400 block mb-1">Realized Cash (Est)</label>
                        <div id="wonReceivedInrPreview" class="text-xs font-black text-emerald-600 dark:text-emerald-400 py-1.5 px-2 bg-emerald-50 dark:bg-emerald-500/10 rounded-lg border border-emerald-200 dark:border-emerald-500/20">
                            ₹0.00
                        </div>
                    </div>
                </div>

                <div>
                    <label class="text-[11px] font-semibold text-gray-600 dark:text-gray-400 block mb-1">Payment Note (optional)</label>
                    <input type="text" id="wonPaymentNoteInput" placeholder="e.g. Initial deposit on signing" class="w-full text-xs py-1.5 px-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>
        </div>

        <!-- 3. Actions -->
        <div class="flex justify-end gap-3">
            <button onclick="closeWonModal()" type="button" class="px-4 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                Cancel
            </button>
            <button id="wonConfirmBtn" type="button" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm shadow-emerald-500/20">
                Confirm & Mark Won
            </button>
        </div>
    </div>
</div>

<script>
    const liveRates = {
        'INR': 1.0000,
        'AED': {{ (float) $aedRate }},
        'USD': {{ (float) $usdRate }}
    };

    let isGlobalPaymentSubmitting = false;

    function resetGlobalPaymentSubmitBtn() {
        isGlobalPaymentSubmitting = false;
        const btn = document.getElementById('globalPaymentSubmitBtn');
        if (btn) {
            btn.disabled = false;
            btn.classList.remove('opacity-75', 'cursor-not-allowed', 'pointer-events-none');
            btn.innerHTML = 'Lock & Save Payment';
        }
    }

    const globalPaymentForm = document.getElementById('globalPaymentForm');
    if (globalPaymentForm) {
        globalPaymentForm.addEventListener('submit', function (e) {
            if (this.checkValidity && !this.checkValidity()) {
                return;
            }
            if (isGlobalPaymentSubmitting) {
                e.preventDefault();
                return false;
            }
            isGlobalPaymentSubmitting = true;
            const btn = document.getElementById('globalPaymentSubmitBtn');
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-75', 'cursor-not-allowed', 'pointer-events-none');
                btn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Recording Payment...</span>
                `;
            }
        });
    }

    window.addEventListener('pageshow', function () {
        resetGlobalPaymentSubmitBtn();
    });

    function openGlobalPaymentModal() {
        resetGlobalPaymentSubmitBtn();
        document.getElementById('globalPaymentModal').classList.remove('hidden');
    }
    function closeGlobalPaymentModal() {
        document.getElementById('globalPaymentModal').classList.add('hidden');
        resetGlobalPaymentSubmitBtn();
    }

    function openLeadModal() {
        document.getElementById('addLeadModal').classList.remove('hidden');
    }
    function closeLeadModal() {
        document.getElementById('addLeadModal').classList.add('hidden');
    }

    function openLeadPaymentModal(id, name, currency, deal, received, pending) {
        openGlobalPaymentModal();
        const select = document.getElementById('paymentLeadSelect');
        select.value = id;
        onPaymentLeadChange();
    }

    function onPaymentLeadChange() {
        const select = document.getElementById('paymentLeadSelect');
        const selected = select.options[select.selectedIndex];
        const form = document.getElementById('globalPaymentForm');
        const summary = document.getElementById('leadFinancialSummaryBar');

        if (!selected || !selected.value) {
            summary.classList.add('hidden');
            form.action = '';
            document.getElementById('globalPayAmount').value = '';
            document.getElementById('globalPreviewInr').textContent = '₹0.00';
            document.getElementById('payCurrencyPrefix').textContent = '₹';
            return;
        }

        const leadId = selected.value;
        const currency = selected.getAttribute('data-currency') || 'INR';
        const symbol = selected.getAttribute('data-symbol') || (currency === 'AED' ? 'AED ' : (currency === 'USD' ? '$' : '₹'));
        const deal = parseFloat(selected.getAttribute('data-deal')) || 0;
        const received = parseFloat(selected.getAttribute('data-received')) || 0;
        const pending = parseFloat(selected.getAttribute('data-pending')) || 0;
        const fxRate = parseFloat(selected.getAttribute('data-fx-rate')) || (liveRates[currency] || 1.0000);

        form.action = `{{ url('admin/crm/leads') }}/${leadId}/payments`;

        document.getElementById('summaryDeal').textContent = symbol + deal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('summaryReceived').textContent = symbol + received.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('summaryPending').textContent = symbol + pending.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        summary.classList.remove('hidden');

        document.getElementById('payCurrencyPrefix').textContent = symbol;
        document.getElementById('globalPayFxRate').value = fxRate;

        const amtInput = document.getElementById('globalPayAmount');
        amtInput.max = pending > 0 ? pending : '';
        recalcGlobalInr();
    }

    function fillShortcutPayment(mode) {
        const select = document.getElementById('paymentLeadSelect');
        const selected = select.options[select.selectedIndex];
        if (!selected || !selected.value) {
            alert('Please select a client first.');
            return;
        }
        const pending = parseFloat(selected.getAttribute('data-pending')) || 0;
        const deal = parseFloat(selected.getAttribute('data-deal')) || 0;
        
        let amount = 0;
        if (mode === 'full_pending' || mode === 1.0 || mode === '1.0') {
            amount = pending;
        } else if (mode === '50_deal' || mode === 0.5 || mode === '0.5') {
            amount = Math.round((deal * 0.5) * 100) / 100;
        }
        
        document.getElementById('globalPayAmount').value = amount;
        recalcGlobalInr();
    }

    function recalcGlobalInr() {
        const amt = parseFloat(document.getElementById('globalPayAmount').value) || 0;
        const rate = parseFloat(document.getElementById('globalPayFxRate').value) || 1.0;
        const inr = amt * rate;
        document.getElementById('globalPreviewInr').textContent = '₹' + inr.toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function toggleFxCollapse() {
        const content = document.getElementById('fxCollapseContent');
        const chevron = document.getElementById('fxChevron');
        content.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    }

    function updateLeadStatusLive(leadId, newStatus) {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

        fetch(`{{ url('admin/crm/leads') }}/${leadId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify({ status: newStatus, _token: token })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Status update failed: ' + (data.message || 'Error'));
            }
        })
        .catch(err => {
            console.error(err);
            alert('Server error updating status.');
        });
    }

    let activeWonLeadId = null;
    let activeWonCurrency = 'INR';
    let activeWonSymbol = '₹';
    let activeWonReceivedSoFar = 0;
    let activeWonPendingBalance = 0;
    let activeStatusSelect = null;

    function handleLeadStatusChange(leadId, newStatus, currentDealAmount, receivedAmount, pendingAmount, bizName, currency, symbol, selectEl) {
        activeStatusSelect = selectEl;

        if (newStatus === 'Won') {
            activeWonLeadId = leadId;
            activeWonCurrency = currency || 'INR';
            activeWonSymbol = symbol || (currency === 'AED' ? 'AED ' : (currency === 'USD' ? '$' : '₹'));
            activeWonReceivedSoFar = parseFloat(receivedAmount) || 0;
            activeWonPendingBalance = parseFloat(pendingAmount) || Math.max(0, (currentDealAmount || 0) - activeWonReceivedSoFar);

            document.getElementById('wonModalBizName').textContent = bizName + ' (' + activeWonCurrency + ')';
            document.getElementById('wonCurrencyLabel').textContent = activeWonCurrency;
            document.getElementById('wonCurrencyPrefix').textContent = activeWonSymbol;
            document.getElementById('wonReceivedCurrencyPrefix').textContent = activeWonSymbol;

            document.getElementById('wonDealAmountInput').value = currentDealAmount || 0;
            document.getElementById('wonDealAmountError').classList.add('hidden');

            // Reset optional payment fields
            const paymentCheckbox = document.getElementById('wonRecordPaymentCheckbox');
            if (paymentCheckbox) paymentCheckbox.checked = false;
            document.getElementById('wonPaymentFields').classList.add('hidden');
            document.getElementById('wonAmountReceivedInput').value = '';
            document.getElementById('wonPaymentError').classList.add('hidden');
            document.getElementById('wonPaymentNoteInput').value = '';

            recalcWonStatus();

            document.getElementById('wonConfirmBtn').disabled = false;
            document.getElementById('wonConfirmBtn').textContent = 'Confirm & Mark Won';

            document.getElementById('wonConfirmBtn').onclick = function() {
                submitWonConfirmation();
            };

            document.getElementById('wonConfirmModal').classList.remove('hidden');
            return;
        }

        // For other statuses (New, Contacted, Proposal, Lost)
        updateLeadStatusLive(leadId, newStatus);
    }

    function closeWonModal() {
        document.getElementById('wonConfirmModal').classList.add('hidden');
        if (activeStatusSelect && activeStatusSelect.dataset.previousStatus) {
            activeStatusSelect.value = activeStatusSelect.dataset.previousStatus;
        }
    }

    function toggleWonPaymentFields() {
        const checked = document.getElementById('wonRecordPaymentCheckbox').checked;
        const fields = document.getElementById('wonPaymentFields');
        const confirmBtn = document.getElementById('wonConfirmBtn');

        if (checked) {
            fields.classList.remove('hidden');
            confirmBtn.textContent = 'Confirm Won & Record Payment';
            recalcWonPaymentPreview();
        } else {
            fields.classList.add('hidden');
            confirmBtn.textContent = 'Confirm & Mark Won';
            document.getElementById('wonPaymentError').classList.add('hidden');
        }
    }

    function recalcWonStatus() {
        const dealAmt = parseFloat(document.getElementById('wonDealAmountInput').value) || 0;
        const received = activeWonReceivedSoFar;
        const pending = Math.max(0, round2(dealAmt - received));
        activeWonPendingBalance = pending;

        document.getElementById('wonModalReceivedAmount').textContent = activeWonSymbol + received.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('wonModalPendingAmount').textContent = activeWonSymbol + pending.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});

        const statusBadge = document.getElementById('wonStatusPreviewBadge');
        const optionalSection = document.getElementById('wonOptionalPaymentSection');
        const fullyPaidNotice = document.getElementById('wonFullyPaidNotice');

        if (dealAmt > 0 && received >= dealAmt) {
            statusBadge.className = 'px-2.5 py-0.5 rounded-full font-bold text-[10px] bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300';
            statusBadge.textContent = 'Paid (100% Settled)';
            if (optionalSection) optionalSection.classList.add('hidden');
            if (fullyPaidNotice) fullyPaidNotice.classList.remove('hidden');
            const paymentCheckbox = document.getElementById('wonRecordPaymentCheckbox');
            if (paymentCheckbox && paymentCheckbox.checked) {
                paymentCheckbox.checked = false;
                toggleWonPaymentFields();
            }
        } else if (received > 0) {
            statusBadge.className = 'px-2.5 py-0.5 rounded-full font-bold text-[10px] bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-300';
            statusBadge.textContent = 'Partial';
            if (optionalSection) optionalSection.classList.remove('hidden');
            if (fullyPaidNotice) fullyPaidNotice.classList.add('hidden');
        } else {
            statusBadge.className = 'px-2.5 py-0.5 rounded-full font-bold text-[10px] bg-gray-100 text-gray-800';
            statusBadge.textContent = 'Pending (Unpaid)';
            if (optionalSection) optionalSection.classList.remove('hidden');
            if (fullyPaidNotice) fullyPaidNotice.classList.add('hidden');
        }

        document.getElementById('wonAmountReceivedInput').max = pending > 0 ? pending : 0;
        document.getElementById('wonMaxPayableNotice').textContent = 'Max: ' + activeWonSymbol + pending.toLocaleString('en-IN', {minimumFractionDigits: 2});

        recalcWonPaymentPreview();
    }

    function recalcWonPaymentPreview() {
        const payAmt = parseFloat(document.getElementById('wonAmountReceivedInput').value) || 0;
        const rate = liveRates[activeWonCurrency] || 1.0;
        const inr = payAmt * rate;
        document.getElementById('wonReceivedInrPreview').textContent = '₹' + inr.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    function round2(val) {
        return Math.round(val * 100) / 100;
    }

    async function submitWonConfirmation() {
        const dealAmt = parseFloat(document.getElementById('wonDealAmountInput').value);
        if (isNaN(dealAmt) || dealAmt < 0) {
            document.getElementById('wonDealAmountError').textContent = 'Please enter a valid deal amount (min 0).';
            document.getElementById('wonDealAmountError').classList.remove('hidden');
            return;
        }

        // Deal amount cannot be reduced below already received payments
        if (activeWonReceivedSoFar > 0 && round2(dealAmt) < round2(activeWonReceivedSoFar)) {
            document.getElementById('wonDealAmountError').textContent = 'Deal amount cannot be less than already received payments (' + activeWonSymbol + activeWonReceivedSoFar.toFixed(2) + ').';
            document.getElementById('wonDealAmountError').classList.remove('hidden');
            return;
        }
        document.getElementById('wonDealAmountError').classList.add('hidden');

        const recordPayment = document.getElementById('wonRecordPaymentCheckbox')?.checked || false;
        let paymentAmount = 0;
        let paymentDate = null;
        let paymentNote = null;

        if (recordPayment) {
            paymentAmount = parseFloat(document.getElementById('wonAmountReceivedInput').value) || 0;
            if (paymentAmount <= 0) {
                document.getElementById('wonPaymentError').textContent = 'Please enter a payment amount greater than zero.';
                document.getElementById('wonPaymentError').classList.remove('hidden');
                return;
            }
            if (round2(paymentAmount) > round2(activeWonPendingBalance)) {
                document.getElementById('wonPaymentError').textContent = 'Payment cannot exceed remaining pending balance (' + activeWonSymbol + activeWonPendingBalance.toFixed(2) + ').';
                document.getElementById('wonPaymentError').classList.remove('hidden');
                return;
            }
            document.getElementById('wonPaymentError').classList.add('hidden');
            paymentDate = document.getElementById('wonPaymentDateInput').value;
            paymentNote = document.getElementById('wonPaymentNoteInput').value;
        }

        const confirmBtn = document.getElementById('wonConfirmBtn');
        confirmBtn.disabled = true;
        confirmBtn.textContent = 'Updating...';

        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

        try {
            // 1. Update lead status to Won (pure status update, no automatic payment)
            const statusRes = await fetch(`{{ url('admin/crm/leads') }}/${activeWonLeadId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                },
                body: JSON.stringify({
                    status: 'Won',
                    deal_amount: dealAmt,
                    _token: token
                })
            });

            const statusData = await statusRes.json();
            if (!statusRes.ok || !statusData.success) {
                alert(statusData.message || 'Failed to update lead status to Won.');
                confirmBtn.disabled = false;
                confirmBtn.textContent = recordPayment ? 'Confirm Won & Record Payment' : 'Confirm & Mark Won';
                return;
            }

            // 2. If optional payment was explicitly requested, record it via standard payment endpoint
            if (recordPayment && paymentAmount > 0) {
                confirmBtn.textContent = 'Recording Payment...';
                const payRes = await fetch(`{{ url('admin/crm/leads') }}/${activeWonLeadId}/payments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify({
                        amount: paymentAmount,
                        paid_on: paymentDate,
                        note: paymentNote || 'Payment upon Won deal closure',
                        _token: token
                    })
                });

                const payData = await payRes.json();
                if (!payRes.ok || !payData.success) {
                    alert('Lead marked as Won, but payment recording failed: ' + (payData.message || 'Validation error'));
                    location.reload();
                    return;
                }
            }

            location.reload();
        } catch (err) {
            console.error(err);
            alert('An unexpected error occurred while updating status.');
            confirmBtn.disabled = false;
            confirmBtn.textContent = recordPayment ? 'Confirm Won & Record Payment' : 'Confirm & Mark Won';
        }
    }
</script>
@endsection
