@extends('layouts.admin')

@section('title', 'CRM & Google Sheets Settings')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">CRM & Google Sheets Integration</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Configure automated real-time Google Sheets backup, default currencies, and view sync health.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.crm.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1.5"></i> Dashboard
            </a>
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
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Sync Status Banner -->
    <div class="p-5 rounded-2xl border {{ $isConfigured ? 'bg-emerald-50 dark:bg-emerald-500/10 border-emerald-200 dark:border-emerald-500/20' : 'bg-amber-50 dark:bg-amber-500/10 border-amber-200 dark:border-amber-500/20' }} flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl {{ $isConfigured ? 'bg-emerald-500 text-white' : 'bg-amber-500 text-white' }} flex items-center justify-center">
                <i data-lucide="{{ $isConfigured ? 'cloud-check' : 'cloud-off' }}" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="font-bold text-sm {{ $isConfigured ? 'text-emerald-900 dark:text-emerald-300' : 'text-amber-900 dark:text-amber-300' }}">
                    Google Sheets Sync: {{ $isConfigured ? 'Active & Connected' : 'Sync Inactive / Configuration Required' }}
                </h3>
                <p class="text-xs {{ $isConfigured ? 'text-emerald-700 dark:text-emerald-400' : 'text-amber-700 dark:text-amber-400' }}">
                    {{ $isConfigured ? 'Changes in CRM will automatically push to tabs \'lead\' and \'Payments\'.' : 'Provide your Google Sheet ID and Service Account JSON credentials below to enable automatic synchronization.' }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <form action="{{ route('admin.crm.settings.resync') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5 mr-1.5"></i> Resync All to Sheet
                </button>
            </form>

            <form action="{{ route('admin.crm.settings.import-sheet') }}" method="POST" class="inline">
                @csrf
                <button type="submit" onclick="return confirm('Import leads & payments from Google Sheet into CRM?');" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                    <i data-lucide="download" class="w-3.5 h-3.5 mr-1.5"></i> Pull From Sheet
                </button>
            </form>
        </div>
    </div>

    <!-- Configuration Form & Sheet Spec Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Settings Form -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <h3 class="font-bold text-gray-900 dark:text-white text-base mb-4 flex items-center gap-2">
                <i data-lucide="sliders" class="w-4 h-4 text-emerald-500"></i>
                General CRM & Google Sheets Settings
            </h3>

            <form action="{{ route('admin.crm.settings.update') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Default Currency -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                        Default Currency for New Leads *
                    </label>
                    <select name="default_currency" class="w-full text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="INR" {{ $defaultCurrency === 'INR' ? 'selected' : '' }}>INR (₹) - Indian Rupee</option>
                        <option value="AED" {{ $defaultCurrency === 'AED' ? 'selected' : '' }}>AED - United Arab Emirates Dirham</option>
                        <option value="USD" {{ $defaultCurrency === 'USD' ? 'selected' : '' }}>USD ($) - US Dollar</option>
                    </select>
                </div>

                <!-- Google Sheet ID -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                        Google Sheet ID
                    </label>
                    <input type="text" name="google_sheet_id" value="{{ $googleSheetId }}" placeholder="e.g. 1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms" class="w-full text-xs font-mono px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                    <span class="text-[11px] text-gray-400 mt-0.5 block">Found in the Google Sheet URL: https://docs.google.com/spreadsheets/d/<strong>{ID}</strong>/edit</span>
                </div>

                <!-- Google Service Account JSON -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                        Google Service Account Credentials (JSON)
                    </label>
                    <textarea name="google_service_account_json" rows="4" placeholder='Paste your Google Cloud Service Account JSON key content or filepath (e.g. {"type": "service_account", ...})' class="w-full text-xs font-mono px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">{{ $googleServiceAccountJson }}</textarea>
                    <span class="text-[11px] text-gray-400 mt-0.5 block">Make sure to share your Google Sheet with the <code>client_email</code> as Editor!</span>
                </div>

                <!-- Enable Sync Checkbox -->
                <div class="pt-2">
                    <label class="inline-flex items-center text-xs font-semibold text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="google_sync_enabled" value="1" {{ $googleSyncEnabled ? 'checked' : '' }} class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 mr-2 w-4 h-4">
                        Enable Automatic Google Sheets Sync on Create / Update / Delete
                    </label>
                </div>

                <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition-colors">
                        Save CRM Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- Google Sheet Format Spec Card -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm space-y-4">
            <h3 class="font-bold text-gray-900 dark:text-white text-sm flex items-center gap-2">
                <i data-lucide="file-spreadsheet" class="w-4 h-4 text-emerald-500"></i>
                Required Google Sheet Layout
            </h3>

            <div class="text-xs text-gray-600 dark:text-gray-300 space-y-3">
                <p>To ensure zero data corruption, your Google Sheet must contain two exact tabs:</p>

                <!-- Tab 1 -->
                <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-700/40 border border-gray-200 dark:border-gray-700">
                    <span class="font-bold text-gray-900 dark:text-white block">1. Tab Name: <code>lead</code></span>
                    <span class="text-[11px] text-gray-400 block mb-1">Header Row: 6 &bull; Data Rows: 7+</span>
                    <ol class="list-decimal list-inside text-[11px] text-gray-500 dark:text-gray-400 space-y-0.5">
                        <li>Business Name</li>
                        <li>Market (India/Dubai/Other)</li>
                        <li>Service (Website/Laravel/Automation)</li>
                        <li>Contact (WhatsApp/Instagram/Email/Call)</li>
                        <li>Website URL</li>
                        <li>Problem Found</li>
                        <li>Message Sent</li>
                        <li>Response</li>
                        <li>Follow-up Date</li>
                        <li>Status</li>
                        <li>Currency</li>
                        <li>Deal Amount</li>
                        <li>Amount Received</li>
                        <li>Pending Payment</li>
                        <li>Payment Status</li>
                    </ol>
                </div>

                <!-- Tab 2 -->
                <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-700/40 border border-gray-200 dark:border-gray-700">
                    <span class="font-bold text-gray-900 dark:text-white block">2. Tab Name: <code>Payments</code></span>
                    <span class="text-[11px] text-gray-400 block mb-1">Header Row: 1 &bull; Data Rows: 2+</span>
                    <ol class="list-decimal list-inside text-[11px] text-gray-500 dark:text-gray-400 space-y-0.5">
                        <li>Payment ID</li>
                        <li>Date</li>
                        <li>Business Name</li>
                        <li>Currency</li>
                        <li>Amount Received</li>
                        <li>FX Rate Used</li>
                        <li>Received INR</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity & Sync Error Logs -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700 mb-4">
            <div class="flex items-center gap-2">
                <i data-lucide="history" class="w-4 h-4 text-gray-400"></i>
                <h3 class="font-bold text-gray-900 dark:text-white text-sm">Sync Activity & Error Logs</h3>
            </div>
            <span class="text-xs text-gray-400">Latest 20 audit logs</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="text-gray-400 uppercase text-[10px] border-b border-gray-100 dark:border-gray-700">
                        <th class="pb-2.5">Time</th>
                        <th class="pb-2.5">Entity</th>
                        <th class="pb-2.5">Action</th>
                        <th class="pb-2.5">Status</th>
                        <th class="pb-2.5">Details / Error Message</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    @forelse($syncLogs as $log)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20">
                            <td class="py-2.5 text-gray-400 whitespace-nowrap">
                                {{ $log->created_at->format('d M H:i:s') }}
                            </td>
                            <td class="py-2.5 font-semibold text-gray-800 dark:text-gray-200">
                                {{ ucfirst($log->entity_type) }}
                            </td>
                            <td class="py-2.5 font-mono text-[11px] text-gray-600 dark:text-gray-400">
                                {{ $log->action }}
                            </td>
                            <td class="py-2.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $log->status === 'success' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300' : 'bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-300' }}">
                                    {{ $log->status }}
                                </span>
                            </td>
                            <td class="py-2.5 text-gray-500 dark:text-gray-400 max-w-md truncate">
                                {{ $log->error_message ?? ($log->payload ? json_encode($log->payload) : 'Completed cleanly.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-400">
                                No sync logs recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
