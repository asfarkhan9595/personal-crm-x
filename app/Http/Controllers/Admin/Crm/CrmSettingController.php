<?php

namespace App\Http\Controllers\Admin\Crm;

use App\Http\Controllers\Controller;
use App\Models\CrmSetting;
use App\Models\CrmSyncLog;
use App\Services\CrmGoogleSheetService;
use Illuminate\Http\Request;

class CrmSettingController extends Controller
{
    public function index()
    {
        $defaultCurrency = CrmSetting::get('default_currency', config('crm.default_currency', 'INR'));
        $googleSheetId = CrmSetting::get('google_sheet_id', config('crm.google_sheet_id', ''));
        $googleServiceAccountJson = CrmSetting::get('google_service_account_json', config('crm.google_service_account_json', ''));
        $googleSyncEnabled = filter_var(CrmSetting::get('google_sync_enabled', config('crm.google_sync_enabled', false)), FILTER_VALIDATE_BOOLEAN);

        $sheetService = app(CrmGoogleSheetService::class);
        $isConfigured = $sheetService->isConfigured();

        $syncLogs = CrmSyncLog::orderBy('created_at', 'desc')->limit(20)->get();

        return view('admin.crm.settings.index', compact(
            'defaultCurrency',
            'googleSheetId',
            'googleServiceAccountJson',
            'googleSyncEnabled',
            'isConfigured',
            'syncLogs'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'default_currency' => 'required|in:INR,AED,USD',
            'google_sheet_id' => 'nullable|string|max:255',
            'google_service_account_json' => 'nullable|string',
            'google_sync_enabled' => 'nullable|boolean',
        ]);

        CrmSetting::set('default_currency', $request->default_currency);
        CrmSetting::set('google_sheet_id', trim($request->google_sheet_id ?? ''));
        CrmSetting::set('google_service_account_json', trim($request->google_service_account_json ?? ''));
        CrmSetting::set('google_sync_enabled', $request->has('google_sync_enabled') ? '1' : '0');

        return redirect()->route('admin.crm.settings.index')
            ->with('success', 'CRM settings saved successfully!');
    }

    public function resyncAll(CrmGoogleSheetService $sheetService)
    {
        if (!$sheetService->isConfigured()) {
            return redirect()->back()->with('error', 'Google Sheets sync is not enabled or credentials are missing.');
        }

        $leadRes = $sheetService->syncAllLeads();
        $paymentRes = $sheetService->syncAllPayments();

        if ($leadRes['success'] && $paymentRes['success']) {
            return redirect()->back()->with('success', 'All Leads and Payments resynced to Google Sheets successfully!');
        }

        $errorMsg = ($leadRes['message'] ?? '') . ' ' . ($paymentRes['message'] ?? '');
        return redirect()->back()->with('error', 'Sync encountered an issue: ' . $errorMsg);
    }

    public function importSheet(CrmGoogleSheetService $sheetService)
    {
        $result = $sheetService->importFromSheet();

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }
}
