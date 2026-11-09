@extends('layouts.admin')

@section('title', 'CRM Payments Ledger')

@section('content')
<div class="space-y-6">
    <!-- Header with Stats & Export -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Payments & FX Ledger</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Audit transactions, locked exchange rates, and bank realizations.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.crm.payments.export-csv', request()->query()) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm shadow-emerald-500/20">
                <i data-lucide="download" class="w-4 h-4 mr-1.5"></i> Export CSV
            </a>
            <a href="{{ route('admin.crm.leads.index') }}" class="inline-flex items-center px-3.5 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1.5"></i> Leads
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Total Realized Banner -->
    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                <i data-lucide="shield-check" class="w-6 h-6"></i>
            </div>
            <div>
                <span class="text-xs uppercase font-bold text-gray-400 tracking-wider">Filtered Payments Cash Total</span>
                <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400">
                    ₹{{ number_format($totalInr, 2) }}
                </div>
            </div>
        </div>
        <div class="text-xs text-gray-500 dark:text-gray-400 text-right">
            Showing {{ $payments->total() }} recorded transaction(s)<br>
            <span class="text-[11px] text-gray-400">Exchange rates permanently locked at transaction day.</span>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <form method="GET" action="{{ route('admin.crm.payments.index') }}" class="flex flex-wrap items-center gap-3 text-xs">
            <!-- Search -->
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-2.5 text-gray-400"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search business name..." class="w-full pl-9 pr-3 py-1.5 text-xs rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>

            <!-- Currency -->
            <select name="currency" onchange="this.form.submit()" class="text-xs px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                <option value="">All Currencies</option>
                <option value="INR" {{ $currency === 'INR' ? 'selected' : '' }}>INR (₹)</option>
                <option value="AED" {{ $currency === 'AED' ? 'selected' : '' }}>AED</option>
                <option value="USD" {{ $currency === 'USD' ? 'selected' : '' }}>USD ($)</option>
            </select>

            <!-- Start Date -->
            <div class="flex items-center gap-1.5">
                <span class="text-gray-400">From:</span>
                <input type="date" name="start_date" value="{{ $startDate }}" class="text-xs px-2.5 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
            </div>

            <!-- End Date -->
            <div class="flex items-center gap-1.5">
                <span class="text-gray-400">To:</span>
                <input type="date" name="end_date" value="{{ $endDate }}" class="text-xs px-2.5 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
            </div>

            <button type="submit" class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                Filter
            </button>

            @if($search || $currency || $startDate || $endDate)
                <a href="{{ route('admin.crm.payments.index') }}" class="text-xs text-red-500 hover:underline py-1.5 px-2">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Payments Ledger Table -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 uppercase text-[10px] tracking-wider">
                        <th class="px-5 py-3.5">ID</th>
                        <th class="px-5 py-3.5">Date</th>
                        <th class="px-5 py-3.5">Client / Business</th>
                        <th class="px-4 py-3.5">Currency</th>
                        <th class="px-4 py-3.5">Amount Received</th>
                        <th class="px-4 py-3.5">FX Rate Used</th>
                        <th class="px-5 py-3.5">Received INR</th>
                        <th class="px-5 py-3.5">Transaction Note</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-5 py-3.5 text-gray-400 font-mono text-[11px]">
                                #{{ $payment->id }}
                            </td>
                            <td class="px-5 py-3.5 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                {{ $payment->paid_on ? $payment->paid_on->format('d M Y') : 'N/A' }}
                            </td>
                            <td class="px-5 py-3.5">
                                @if($payment->lead)
                                    <a href="{{ route('admin.crm.leads.show', $payment->lead_id) }}" class="font-bold text-gray-900 dark:text-white hover:text-emerald-600 dark:hover:text-emerald-400">
                                        {{ $payment->lead->business_name }}
                                    </a>
                                @else
                                    <span class="text-gray-400">Deleted Lead (#{{ $payment->lead_id }})</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 font-semibold text-gray-700 dark:text-gray-300">
                                <span class="px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-[10px]">
                                    {{ $payment->currency }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 font-bold text-gray-900 dark:text-white">
                                {{ $payment->formatted_amount }}
                            </td>
                            <td class="px-4 py-3.5 font-mono text-gray-600 dark:text-gray-400">
                                {{ $payment->fx_rate }}
                            </td>
                            <td class="px-5 py-3.5 font-black text-emerald-600 dark:text-emerald-400">
                                {{ $payment->formatted_received_inr }}
                            </td>
                            <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                {{ $payment->note ?? '-' }}
                            </td>
                            <td class="px-5 py-3.5 text-right space-x-2 whitespace-nowrap">
                                <button onclick="openEditPaymentModal({{ $payment->id }}, '{{ $payment->paid_on->format('Y-m-d') }}', {{ $payment->amount }}, {{ $payment->fx_rate }}, '{{ addslashes($payment->note ?? '') }}', '{{ $payment->currency }}')" type="button" class="p-1.5 text-gray-400 hover:text-indigo-600 transition-colors" title="Edit Date/Rate">
                                    <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                </button>
                                <form action="{{ route('admin.crm.payments.destroy', $payment->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this payment record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 transition-colors" title="Delete">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-10 text-gray-400">
                                No payment records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $payments->links() }}
        </div>
    </div>
</div>

<!-- Edit Payment Modal -->
<div id="editPaymentModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
    <div onclick="closeEditPaymentModal()" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="relative z-10 w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700 mb-4">
            <h3 class="text-base font-bold text-gray-900 dark:text-white">Edit Payment & FX Rate</h3>
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
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Amount (<span id="editCurrencyLabel">INR</span>)</label>
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

<script>
    function openEditPaymentModal(id, date, amount, rate, note, currency) {
        const form = document.getElementById('editPaymentForm');
        form.action = `{{ url('admin/crm/payments') }}/${id}`;
        document.getElementById('editPayDate').value = date;
        document.getElementById('editPayAmount').value = amount;
        document.getElementById('editPayFxRate').value = rate;
        document.getElementById('editPayNote').value = note;
        document.getElementById('editCurrencyLabel').textContent = currency || 'INR';
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
</script>
@endsection
