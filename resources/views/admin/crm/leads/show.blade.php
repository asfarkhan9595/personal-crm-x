@extends('layouts.admin')

@section('title', $lead->business_name . ' - Lead Details')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-1">
                <a href="{{ route('admin.crm.leads.index') }}" class="hover:text-emerald-600 transition-colors">Leads</a>
                <span>/</span>
                <span class="text-gray-900 dark:text-white font-medium">{{ $lead->business_name }}</span>
            </div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $lead->business_name }}</h1>
                <span class="px-2.5 py-0.5 text-xs font-bold rounded-full 
                    {{ $lead->status === 'Won' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300' : 
                       ($lead->status === 'Lost' ? 'bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-300' : 
                       ($lead->status === 'Proposal' ? 'bg-purple-100 text-purple-800 dark:bg-purple-500/20 dark:text-purple-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-500/20 dark:text-blue-300')) }}">
                    {{ $lead->status }}
                </span>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            @if($lead->status !== 'Lost')
            <button onclick="openPaymentModal()" type="button" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm shadow-emerald-500/20">
                <i data-lucide="plus-circle" class="w-4 h-4 mr-1.5"></i> + Record Payment
            </button>
            @endif
            <button onclick="openEditLeadModal()" type="button" class="inline-flex items-center px-3.5 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition-colors">
                <i data-lucide="pencil" class="w-4 h-4 mr-1.5"></i> Edit Lead
            </button>
            @php
                $hasPayments = $lead->payments->isNotEmpty();
                $isWon = $lead->status === 'Won';
                $isDeleteBlocked = $hasPayments || $isWon;
                $deleteReason = $hasPayments 
                    ? "Cannot delete: {$lead->payments->count()} recorded payment(s) in financial ledger." 
                    : ($isWon ? "Cannot delete a Won deal." : "");
            @endphp
            @if($isDeleteBlocked)
                <span class="inline-flex items-center p-2 text-gray-400 bg-gray-100 dark:bg-gray-800 rounded-lg cursor-not-allowed text-xs font-medium" title="{{ $deleteReason }}">
                    <i data-lucide="lock" class="w-4 h-4 mr-1"></i> Locked
                </span>
            @else
                <form action="{{ route('admin.crm.leads.destroy', $lead->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete lead \'{{ addslashes($lead->business_name) }}\'?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors" title="Delete Lead">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/20 flex items-center gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0"></i>
            <span class="text-xs font-semibold">{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/20">
            <ul class="list-disc list-inside text-xs space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Deal & Payment Accounting Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <!-- Deal Amount -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Deal Value</span>
            <div class="text-2xl font-black text-gray-900 dark:text-white mt-1">{{ $lead->formatted_deal_amount }}</div>
            <span class="text-[11px] text-gray-400">{{ $lead->currency }} contract agreement</span>
        </div>

        <!-- Total Received -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <span class="text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Amount Received</span>
            <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">
                {{ $lead->currency_symbol }}{{ number_format($lead->amount_received, 2) }}
            </div>
            <span class="text-[11px] text-gray-400">{{ $lead->payments->count() }} installment(s) paid</span>
        </div>

        <!-- Pending Balance -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <span class="text-xs font-semibold uppercase tracking-wider text-amber-500">Remaining Pending</span>
            <div class="text-2xl font-black text-amber-600 dark:text-amber-400 mt-1">
                {{ $lead->currency_symbol }}{{ number_format($lead->pending_payment, 2) }}
            </div>
            <span class="text-[11px] text-gray-400">Outstanding collectible</span>
        </div>

        <!-- Payment Status Badge -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Payment Status</span>
            <div class="mt-1">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-black
                    {{ $lead->payment_status === 'Paid' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300' : 
                       ($lead->payment_status === 'Partial' ? 'bg-blue-100 text-blue-800 dark:bg-blue-500/20 dark:text-blue-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-300') }}">
                    {{ $lead->payment_status }}
                </span>
            </div>
            <span class="text-[11px] text-gray-400">Auto-calculated from payments</span>
        </div>
    </div>

    <!-- Lead Details & Audit Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Lead Profile Info Card -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm space-y-4">
            <h3 class="font-bold text-gray-900 dark:text-white text-sm border-b border-gray-100 dark:border-gray-700 pb-3 flex items-center justify-between">
                <span>Client Profile</span>
                <span class="text-[11px] font-normal text-gray-400">ID #{{ $lead->id }}</span>
            </h3>

            <div class="grid grid-cols-2 gap-3 text-xs">
                <div>
                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Target Market</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $lead->market }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Service Type</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $lead->service }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Primary Channel</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $lead->contact_channel }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Client Response</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $lead->response }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Initial Outreach</span>
                    <span class="font-semibold {{ $lead->message_sent ? 'text-emerald-600' : 'text-gray-500' }}">
                        {{ $lead->message_sent ? 'Message Sent' : 'Pending Outreach' }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Next Follow-up</span>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        {{ $lead->follow_up_date ? $lead->follow_up_date->format('d M Y') : 'Not scheduled' }}
                    </span>
                </div>
            </div>

            @if($lead->website_url)
                <div class="pt-2 border-t border-gray-100 dark:border-gray-700/60">
                    <span class="text-gray-400 block text-[10px] uppercase font-bold mb-1">Audit Website</span>
                    <a href="{{ $lead->website_url }}" target="_blank" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1 truncate">
                        <i data-lucide="external-link" class="w-3.5 h-3.5 flex-shrink-0"></i>
                        <span>{{ $lead->website_url }}</span>
                    </a>
                </div>
            @endif

            @if($lead->problem_found)
                <div class="pt-2 border-t border-gray-100 dark:border-gray-700/60">
                    <span class="text-gray-400 block text-[10px] uppercase font-bold mb-1">Identified Problem / Pain Points</span>
                    <div class="text-xs p-3 rounded-lg bg-gray-50 dark:bg-gray-700/40 text-gray-700 dark:text-gray-300">
                        {{ $lead->problem_found }}
                    </div>
                </div>
            @endif

            @if($lead->notes)
                <div class="pt-2 border-t border-gray-100 dark:border-gray-700/60">
                    <span class="text-gray-400 block text-[10px] uppercase font-bold mb-1">Deal Notes</span>
                    <div class="text-xs text-gray-600 dark:text-gray-400 whitespace-pre-line">
                        {{ $lead->notes }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Payments Ledger Table -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700 mb-4">
                    <div class="flex items-center gap-2">
                        <i data-lucide="receipt" class="w-4 h-4 text-emerald-500"></i>
                        <h3 class="font-bold text-gray-900 dark:text-white text-sm">Payment Installments</h3>
                    </div>
                    @if($lead->status !== 'Lost')
                    <button onclick="openPaymentModal()" type="button" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline">
                        + Add Installment
                    </button>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="text-gray-400 uppercase text-[10px] border-b border-gray-100 dark:border-gray-700">
                                <th class="pb-2.5">Date</th>
                                <th class="pb-2.5">Amount ({{ $lead->currency }})</th>
                                <th class="pb-2.5">Locked FX Rate</th>
                                <th class="pb-2.5">Realized INR</th>
                                <th class="pb-2.5">Note</th>
                                <th class="pb-2.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                            @forelse($lead->payments as $payment)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20">
                                    <td class="py-3 font-medium text-gray-900 dark:text-white">
                                        {{ $payment->paid_on ? $payment->paid_on->format('d M Y') : 'N/A' }}
                                    </td>
                                    <td class="py-3 font-bold text-gray-900 dark:text-white">
                                        {{ $payment->formatted_amount }}
                                    </td>
                                    <td class="py-3 text-gray-600 dark:text-gray-300 font-mono">
                                        {{ $payment->fx_rate }}
                                    </td>
                                    <td class="py-3 font-bold text-emerald-600 dark:text-emerald-400">
                                        {{ $payment->formatted_received_inr }}
                                    </td>
                                    <td class="py-3 text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                        {{ $payment->note ?? '-' }}
                                    </td>
                                    <td class="py-3 text-right space-x-2">
                                        <button onclick="openEditPaymentModal({{ $payment->id }}, '{{ $payment->paid_on->format('Y-m-d') }}', {{ $payment->amount }}, {{ $payment->fx_rate }}, '{{ addslashes($payment->note ?? '') }}')" type="button" class="p-1 text-gray-400 hover:text-indigo-600 transition-colors" title="Edit date/rate">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        </button>
                                        <form action="{{ route('admin.crm.payments.destroy', $payment->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this payment installment?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-gray-400 hover:text-red-600 transition-colors" title="Delete">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8 text-gray-400">
                                        No payments recorded yet for this deal.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Total INR realization footer -->
            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
                <span class="text-xs text-gray-400">Total In Bank (Sum of locked payments):</span>
                <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">
                    ₹{{ number_format($lead->payments->sum('received_inr'), 2) }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Add Payment Modal (Payment Logic) -->
<div id="addPaymentModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
    <div onclick="closePaymentModal()" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="relative z-10 w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700 mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <i data-lucide="receipt" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Record Payment</h3>
                    <p class="text-xs text-gray-400">{{ $lead->business_name }} ({{ $lead->currency }})</p>
                </div>
            </div>
            <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="leadPaymentForm" action="{{ route('admin.crm.payments.store', $lead->id) }}" method="POST" class="space-y-4">
            @csrf
            
            <!-- Amount (Primary asked field) -->
            <div>
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                        Payment Amount in {{ $lead->currency }} *
                    </label>
                    <div class="flex items-center gap-1.5">
                        <button type="button" onclick="document.getElementById('payAmountInput').value = {{ $lead->pending_payment }}; recalculateInr();" class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-100 hover:bg-emerald-200 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300">
                            Pay Full Pending
                        </button>
                        <button type="button" onclick="document.getElementById('payAmountInput').value = Math.round(({{ $lead->pending_payment }} * 0.5) * 100) / 100; recalculateInr();" class="text-[10px] font-bold px-2 py-0.5 rounded bg-indigo-100 hover:bg-indigo-200 text-indigo-800 dark:bg-indigo-500/20 dark:text-indigo-300">
                            50%
                        </button>
                    </div>
                </div>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 font-bold text-gray-500 text-xs">{{ $lead->currency_symbol }}</span>
                    <input type="number" step="0.01" min="0.01" max="{{ $lead->pending_payment > 0 ? $lead->pending_payment : '' }}" 
                           name="amount" id="payAmountInput" required autofocus
                           placeholder="Enter received amount"
                           class="w-full text-sm font-bold pl-10 pr-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500"
                           oninput="recalculateInr()">
                </div>
                <div class="flex justify-between text-[11px] text-gray-400 mt-1">
                    <span>Outstanding: {{ $lead->currency_symbol }}{{ number_format($lead->pending_payment, 2) }}</span>
                    <span class="text-emerald-600">Locked at settlement</span>
                </div>
            </div>

            <!-- Realized INR Live Preview Box -->
            <div class="p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-xs font-medium text-emerald-800 dark:text-emerald-300 block">Realized Cash in Bank:</span>
                        <span class="text-[10px] text-emerald-600/80">FX rate permanently locked on save</span>
                    </div>
                    <span id="previewReceivedInr" class="text-base font-black text-emerald-700 dark:text-emerald-400">₹0.00</span>
                </div>
            </div>

            <!-- Collapsible: Edit Date or Exchange Rate -->
            <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-3 bg-gray-50/50 dark:bg-gray-700/20">
                <button type="button" onclick="toggleLeadFxCollapse()" class="w-full flex items-center justify-between text-xs font-semibold text-gray-600 dark:text-gray-300">
                    <span class="flex items-center gap-1.5">
                        <i data-lucide="sliders" class="w-3.5 h-3.5 text-gray-400"></i> Edit Date or Exchange Rate (Optional)
                    </span>
                    <i id="leadFxChevron" data-lucide="chevron-down" class="w-4 h-4 transition-transform"></i>
                </button>

                <div id="leadFxCollapseContent" class="space-y-3 pt-3 hidden">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Date Paid</label>
                            <input type="date" name="paid_on" id="payDateInput" value="{{ date('Y-m-d') }}" class="w-full text-xs px-2.5 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">FX Rate to INR</label>
                            <input type="number" step="0.0001" min="0.0001" name="fx_rate" id="payFxRateInput" 
                                   value="{{ $todayFxRate }}" {{ $lead->currency === 'INR' ? 'readonly' : '' }}
                                   class="w-full text-xs font-mono px-2.5 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                   oninput="recalculateInr()">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Payment Note</label>
                        <input type="text" name="note" placeholder="Milestone notes / Transaction ID..." class="w-full text-xs px-2.5 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                <button onclick="closePaymentModal()" type="button" class="px-4 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                    Cancel
                </button>
                <button type="submit" id="leadPaymentSubmitBtn" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm flex items-center justify-center">
                    Lock & Save Payment
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Payment Modal -->
<div id="editPaymentModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
    <div onclick="closeEditPaymentModal()" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="relative z-10 w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700 mb-4">
            <h3 class="text-base font-bold text-gray-900 dark:text-white">Edit Payment Installment</h3>
            <button onclick="closeEditPaymentModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="editPaymentForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Date</label>
                <input type="date" name="paid_on" id="editPayDate" required class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Amount ({{ $lead->currency }})</label>
                <input type="number" step="0.01" min="0.01" name="amount" id="editPayAmount" required class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white" oninput="recalculateEditInr()">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">FX Rate to INR</label>
                <input type="number" step="0.0001" min="0.0001" name="fx_rate" id="editPayFxRate" required class="w-full text-xs font-mono px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white" oninput="recalculateEditInr()">
            </div>

            <div class="p-3 rounded-lg bg-gray-100 dark:bg-gray-700/50 flex justify-between items-center text-xs">
                <span class="text-gray-500">Recalculated INR:</span>
                <span id="editPreviewInr" class="font-bold text-emerald-600 dark:text-emerald-400">₹0.00</span>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Note</label>
                <input type="text" name="note" id="editPayNote" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                <button onclick="closeEditPaymentModal()" type="button" class="px-4 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                    Cancel
                </button>
                <button type="submit" onclick="return confirm('Confirm updating this payment rate and amount?');" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm">
                    Update Payment
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Lead Modal -->
<div id="editLeadModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
    <div onclick="closeEditLeadModal()" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="relative z-10 w-full max-w-xl bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700 mb-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Edit Lead Details</h3>
            <button onclick="closeEditLeadModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('admin.crm.leads.update', $lead->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Business Name *</label>
                <input type="text" name="business_name" value="{{ $lead->business_name }}" required class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Market</label>
                    <select name="market" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="India" {{ $lead->market === 'India' ? 'selected' : '' }}>India</option>
                        <option value="Dubai" {{ $lead->market === 'Dubai' ? 'selected' : '' }}>Dubai</option>
                        <option value="Other" {{ $lead->market === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Service</label>
                    <select name="service" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="Website" {{ $lead->service === 'Website' ? 'selected' : '' }}>Website</option>
                        <option value="Laravel" {{ $lead->service === 'Laravel' ? 'selected' : '' }}>Laravel</option>
                        <option value="Automation" {{ $lead->service === 'Automation' ? 'selected' : '' }}>Automation</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Channel</label>
                    <select name="contact_channel" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="WhatsApp" {{ $lead->contact_channel === 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                        <option value="Instagram" {{ $lead->contact_channel === 'Instagram' ? 'selected' : '' }}>Instagram</option>
                        <option value="Email" {{ $lead->contact_channel === 'Email' ? 'selected' : '' }}>Email</option>
                        <option value="Call" {{ $lead->contact_channel === 'Call' ? 'selected' : '' }}>Call</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Website URL</label>
                    <input type="url" name="website_url" value="{{ $lead->website_url }}" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Follow-up Date</label>
                    <input type="date" name="follow_up_date" value="{{ $lead->follow_up_date ? $lead->follow_up_date->format('Y-m-d') : '' }}" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Pipeline Status</label>
                    <select name="status" 
                        @if(in_array($lead->status, ['Won', 'Lost'])) disabled title="{{ $lead->status }} status is locked" @endif
                        class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 
                        {{ in_array($lead->status, ['Won', 'Lost']) ? 'bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-500 cursor-not-allowed font-medium' : 'bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white' }}">
                        <option value="New" {{ $lead->status === 'New' ? 'selected' : '' }}>New</option>
                        <option value="Contacted" {{ $lead->status === 'Contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="Proposal" {{ $lead->status === 'Proposal' ? 'selected' : '' }}>Proposal</option>
                        <option value="Won" {{ $lead->status === 'Won' ? 'selected' : '' }}>Won</option>
                        <option value="Lost" {{ $lead->status === 'Lost' ? 'selected' : '' }}>Lost</option>
                    </select>
                    @if(in_array($lead->status, ['Won', 'Lost']))
                        <input type="hidden" name="status" value="{{ $lead->status }}">
                        <span class="text-[10px] text-gray-400 mt-1 block">Status is permanently locked for {{ $lead->status }} deals.</span>
                    @endif
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Currency</label>
                    @php
                        $hasPayments = $lead->payments()->exists();
                    @endphp
                    @if($hasPayments)
                        <input type="hidden" name="currency" value="{{ $lead->currency }}">
                        <select disabled class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700/80 text-gray-500 dark:text-gray-400 font-bold cursor-not-allowed">
                            <option value="INR" {{ $lead->currency === 'INR' ? 'selected' : '' }}>INR (₹)</option>
                            <option value="AED" {{ $lead->currency === 'AED' ? 'selected' : '' }}>AED</option>
                            <option value="USD" {{ $lead->currency === 'USD' ? 'selected' : '' }}>USD ($)</option>
                        </select>
                        <span class="text-[10px] text-amber-600 dark:text-amber-400 mt-1 block font-medium">
                            Currency locked ({{ $lead->payments()->count() }} payment(s) recorded).
                        </span>
                    @else
                        <select name="currency" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white font-bold">
                            <option value="INR" {{ $lead->currency === 'INR' ? 'selected' : '' }}>INR (₹)</option>
                            <option value="AED" {{ $lead->currency === 'AED' ? 'selected' : '' }}>AED</option>
                            <option value="USD" {{ $lead->currency === 'USD' ? 'selected' : '' }}>USD ($)</option>
                        </select>
                        <span class="text-[10px] text-gray-400 mt-1 block">Editable until first payment is recorded.</span>
                    @endif
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Deal Amount</label>
                    <input type="number" step="0.01" min="0" name="deal_amount" value="{{ $lead->deal_amount }}" required class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Response</label>
                    <select name="response" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="No response" {{ $lead->response === 'No response' ? 'selected' : '' }}>No response</option>
                        <option value="Interested" {{ $lead->response === 'Interested' ? 'selected' : '' }}>Interested</option>
                        <option value="Not interested" {{ $lead->response === 'Not interested' ? 'selected' : '' }}>Not interested</option>
                    </select>
                </div>
                <div class="flex items-center pt-6">
                    <label class="inline-flex items-center text-xs font-medium text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="message_sent" value="1" {{ $lead->message_sent ? 'checked' : '' }} class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 mr-2">
                        Message Sent (Initial Outreach Done)
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Problem Found / Pain Points</label>
                <textarea name="problem_found" rows="2" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">{{ $lead->problem_found }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                <textarea name="notes" rows="2" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">{{ $lead->notes }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                <button onclick="closeEditLeadModal()" type="button" class="px-4 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let isLeadPaymentSubmitting = false;

    function resetLeadPaymentSubmitBtn() {
        isLeadPaymentSubmitting = false;
        const btn = document.getElementById('leadPaymentSubmitBtn');
        if (btn) {
            btn.disabled = false;
            btn.classList.remove('opacity-75', 'cursor-not-allowed', 'pointer-events-none');
            btn.innerHTML = 'Lock & Save Payment';
        }
    }

    const leadPaymentForm = document.getElementById('leadPaymentForm');
    if (leadPaymentForm) {
        leadPaymentForm.addEventListener('submit', function (e) {
            if (this.checkValidity && !this.checkValidity()) {
                return;
            }
            if (isLeadPaymentSubmitting) {
                e.preventDefault();
                return false;
            }
            isLeadPaymentSubmitting = true;
            const btn = document.getElementById('leadPaymentSubmitBtn');
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
        resetLeadPaymentSubmitBtn();
    });

    function openPaymentModal() {
        resetLeadPaymentSubmitBtn();
        document.getElementById('addPaymentModal').classList.remove('hidden');
        recalculateInr();
    }
    function closePaymentModal() {
        document.getElementById('addPaymentModal').classList.add('hidden');
        resetLeadPaymentSubmitBtn();
    }

    function toggleLeadFxCollapse() {
        const content = document.getElementById('leadFxCollapseContent');
        const chevron = document.getElementById('leadFxChevron');
        content.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    }

    function recalculateInr() {
        const amt = parseFloat(document.getElementById('payAmountInput').value) || 0;
        const rate = parseFloat(document.getElementById('payFxRateInput').value) || 1;
        const inr = amt * rate;
        document.getElementById('previewReceivedInr').textContent = '₹' + inr.toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function openEditPaymentModal(id, date, amount, rate, note) {
        const form = document.getElementById('editPaymentForm');
        form.action = `{{ url('admin/crm/payments') }}/${id}`;
        document.getElementById('editPayDate').value = date;
        document.getElementById('editPayAmount').value = amount;
        document.getElementById('editPayFxRate').value = rate;
        document.getElementById('editPayNote').value = note;
        recalculateEditInr();
        document.getElementById('editPaymentModal').classList.remove('hidden');
    }
    function closeEditPaymentModal() {
        document.getElementById('editPaymentModal').classList.add('hidden');
    }
    function recalculateEditInr() {
        const amt = parseFloat(document.getElementById('editPayAmount').value) || 0;
        const rate = parseFloat(document.getElementById('editPayFxRate').value) || 1;
        const inr = amt * rate;
        document.getElementById('editPreviewInr').textContent = '₹' + inr.toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function openEditLeadModal() {
        document.getElementById('editLeadModal').classList.remove('hidden');
    }
    function closeEditLeadModal() {
        document.getElementById('editLeadModal').classList.add('hidden');
    }
</script>
@endsection
