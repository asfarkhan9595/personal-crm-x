<?php

namespace App\Services;

use App\Models\CrmLead;
use App\Models\CrmPayment;
use App\Models\CrmSetting;
use App\Models\CrmSyncLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CrmGoogleSheetService
{
    /**
     * Check if Google Sheets sync is enabled and configured
     */
    public function isConfigured(): bool
    {
        $enabled = CrmSetting::get('google_sync_enabled', config('crm.google_sync_enabled', false));
        if ($enabled === 'false' || $enabled === '0' || !$enabled) {
            return false;
        }

        $sheetId = $this->getSheetId();
        $creds = $this->getCredentials();

        return !empty($sheetId) && !empty($creds);
    }

    public function getSheetId(): string
    {
        return (string) (CrmSetting::get('google_sheet_id') ?: config('crm.google_sheet_id', ''));
    }

    /**
     * Parse service account credentials (JSON string, relative path, or absolute path)
     */
    public function getCredentials(): ?array
    {
        $raw = CrmSetting::get('google_service_account_json') ?: config('crm.google_service_account_json', '');
        if (empty($raw)) {
            return null;
        }

        if (is_file(base_path($raw))) {
            $raw = file_get_contents(base_path($raw));
        } elseif (is_file($raw)) {
            $raw = file_get_contents($raw);
        }

        $data = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($data) && isset($data['client_email'], $data['private_key'])) {
            return $data;
        }

        return null;
    }

    /**
     * Generate OAuth2 Access Token using RS256 JWT
     */
    protected function getAccessToken(): ?string
    {
        $creds = $this->getCredentials();
        if (!$creds) {
            return null;
        }

        $cacheKey = 'crm_google_sheets_token_' . md5($creds['client_email']);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $now = time();
        $header = ['alg' => 'RS256', 'typ' => 'JWT'];
        $payload = [
            'iss' => $creds['client_email'],
            'scope' => 'https://www.googleapis.com/auth/spreadsheets',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now,
        ];

        $encodedHeader = $this->base64UrlEncode(json_encode($header));
        $encodedPayload = $this->base64UrlEncode(json_encode($payload));
        $dataToSign = $encodedHeader . '.' . $encodedPayload;

        $binarySignature = '';
        $success = openssl_sign($dataToSign, $binarySignature, $creds['private_key'], OPENSSL_ALGO_SHA256);
        if (!$success) {
            Log::error('CrmGoogleSheetService: Failed to sign OAuth2 JWT with private key.');
            return null;
        }

        $jwt = $dataToSign . '.' . $this->base64UrlEncode($binarySignature);

        $response = Http::asForm()->timeout(10)->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        if ($response->successful()) {
            $json = $response->json();
            $token = $json['access_token'] ?? null;
            $expiresIn = $json['expires_in'] ?? 3600;

            if ($token) {
                Cache::put($cacheKey, $token, max(60, $expiresIn - 300));
                return $token;
            }
        }

        Log::error('CrmGoogleSheetService: OAuth token request failed: ' . $response->body());
        return null;
    }

    protected function base64UrlEncode(string $data): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    /**
     * Push all leads to 'lead' tab starting at row 7
     */
    public function syncAllLeads(): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'message' => 'Google Sheets sync is disabled or unconfigured.'];
        }

        $token = $this->getAccessToken();
        if (!$token) {
            $this->logSync('lead', null, 'sync_all', 'failed', 'Unable to obtain Google OAuth2 token');
            return ['success' => false, 'message' => 'Unable to obtain Google OAuth2 access token.'];
        }

        $sheetId = $this->getSheetId();
        $leads = CrmLead::with('payments')->orderBy('id', 'asc')->get();

        $rows = [];
        foreach ($leads as $lead) {
            $rows[] = [
                $lead->business_name,
                $lead->market,
                $lead->service,
                $lead->contact_channel,
                $lead->website_url ?? '',
                $lead->problem_found ?? '',
                $lead->message_sent ? 'Yes' : 'No',
                $lead->response,
                $lead->follow_up_date ? $lead->follow_up_date->format('Y-m-d') : '',
                $lead->status,
                $lead->currency,
                (float) ($lead->deal_amount ?? 0),
                (float) $lead->amount_received,
                (float) $lead->pending_payment,
                $lead->payment_status,
            ];
        }

        // Write values to 'lead'!A7:O
        $range = 'lead!A7:O' . (count($rows) > 0 ? (6 + count($rows)) : 7);
        $url = "https://sheets.googleapis.com/v4/spreadsheets/{$sheetId}/values/{$range}?valueInputOption=USER_ENTERED";

        $response = Http::withToken($token)->timeout(15)->put($url, [
            'range' => $range,
            'majorDimension' => 'ROWS',
            'values' => $rows,
        ]);

        if ($response->successful()) {
            $this->logSync('lead', null, 'sync_all', 'success', null, ['count' => count($rows)]);
            return ['success' => true, 'message' => 'Synced ' . count($rows) . ' leads to Google Sheet.'];
        }

        $errorMsg = $response->body();
        $this->logSync('lead', null, 'sync_all', 'failed', $errorMsg);
        return ['success' => false, 'message' => 'Google Sheets sync failed: ' . $errorMsg];
    }

    /**
     * Push all payments to 'Payments' tab
     */
    public function syncAllPayments(): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'message' => 'Google Sheets sync is disabled or unconfigured.'];
        }

        $token = $this->getAccessToken();
        if (!$token) {
            $this->logSync('payment', null, 'sync_all', 'failed', 'Unable to obtain Google OAuth2 token');
            return ['success' => false, 'message' => 'Unable to obtain Google OAuth2 access token.'];
        }

        $sheetId = $this->getSheetId();
        $payments = CrmPayment::with('lead')->orderBy('paid_on', 'asc')->get();

        $rows = [];
        foreach ($payments as $payment) {
            $rows[] = [
                $payment->id,
                $payment->paid_on ? $payment->paid_on->format('Y-m-d') : '',
                $payment->lead?->business_name ?? 'N/A',
                $payment->currency,
                (float) $payment->amount,
                (float) $payment->fx_rate,
                (float) $payment->received_inr,
            ];
        }

        $range = 'Payments!A2:G' . (count($rows) > 0 ? (1 + count($rows)) : 2);
        $url = "https://sheets.googleapis.com/v4/spreadsheets/{$sheetId}/values/{$range}?valueInputOption=USER_ENTERED";

        $response = Http::withToken($token)->timeout(15)->put($url, [
            'range' => $range,
            'majorDimension' => 'ROWS',
            'values' => $rows,
        ]);

        if ($response->successful()) {
            $this->logSync('payment', null, 'sync_all', 'success', null, ['count' => count($rows)]);
            return ['success' => true, 'message' => 'Synced ' . count($rows) . ' payments to Google Sheet.'];
        }

        $errorMsg = $response->body();
        $this->logSync('payment', null, 'sync_all', 'failed', $errorMsg);
        return ['success' => false, 'message' => 'Google Sheets sync failed: ' . $errorMsg];
    }

    /**
     * Import leads and payments from Google Sheet
     */
    public function importFromSheet(): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'message' => 'Google Sheets sync is unconfigured.'];
        }

        $token = $this->getAccessToken();
        if (!$token) {
            return ['success' => false, 'message' => 'Unable to get Google OAuth2 token.'];
        }

        $sheetId = $this->getSheetId();

        // 1. Fetch leads starting row 7
        $urlLeads = "https://sheets.googleapis.com/v4/spreadsheets/{$sheetId}/values/lead!A7:O50";
        $resLeads = Http::withToken($token)->timeout(15)->get($urlLeads);

        $importedLeads = 0;
        if ($resLeads->successful()) {
            $rows = $resLeads->json()['values'] ?? [];
            foreach ($rows as $row) {
                $businessName = trim($row[0] ?? '');
                if (empty($businessName)) continue;

                CrmLead::updateOrCreate(
                    ['business_name' => $businessName],
                    [
                        'market' => $row[1] ?? 'India',
                        'service' => $row[2] ?? 'Website',
                        'contact_channel' => $row[3] ?? 'WhatsApp',
                        'website_url' => $row[4] ?? null,
                        'problem_found' => $row[5] ?? null,
                        'message_sent' => strtolower($row[6] ?? '') === 'yes' || strtolower($row[6] ?? '') === 'true',
                        'response' => $row[7] ?? 'No response',
                        'follow_up_date' => !empty($row[8]) ? date('Y-m-d', strtotime($row[8])) : null,
                        'status' => $row[9] ?? 'New',
                        'currency' => strtoupper($row[10] ?? 'INR'),
                        'deal_amount' => (float) ($row[11] ?? 0),
                    ]
                );
                $importedLeads++;
            }
        }

        // 2. Fetch payments
        $urlPayments = "https://sheets.googleapis.com/v4/spreadsheets/{$sheetId}/values/Payments!A2:G50";
        $resPayments = Http::withToken($token)->timeout(15)->get($urlPayments);

        $importedPayments = 0;
        if ($resPayments->successful()) {
            $rows = $resPayments->json()['values'] ?? [];
            foreach ($rows as $row) {
                $bizName = trim($row[2] ?? '');
                $amount = (float) ($row[4] ?? 0);
                if (empty($bizName) || $amount <= 0) continue;

                $lead = CrmLead::where('business_name', $bizName)->first();
                if (!$lead) continue;

                $fxRate = (float) ($row[5] ?? 1.0);
                $receivedInr = (float) ($row[6] ?? ($amount * $fxRate));
                $paidOn = !empty($row[1]) ? date('Y-m-d', strtotime($row[1])) : date('Y-m-d');

                CrmPayment::create([
                    'lead_id' => $lead->id,
                    'paid_on' => $paidOn,
                    'currency' => strtoupper($row[3] ?? $lead->currency),
                    'amount' => $amount,
                    'fx_rate' => $fxRate,
                    'received_inr' => $receivedInr,
                    'note' => 'Imported from Google Sheet',
                ]);
                $importedPayments++;
            }
        }

        $this->logSync('batch', null, 'import', 'success', null, [
            'leads' => $importedLeads,
            'payments' => $importedPayments
        ]);

        return [
            'success' => true,
            'message' => "Imported {$importedLeads} leads and {$importedPayments} payments successfully."
        ];
    }

    /**
     * Record sync logs
     */
    public function logSync(string $entityType, ?int $entityId, string $action, string $status, ?string $errorMessage = null, ?array $payload = null): void
    {
        try {
            CrmSyncLog::create([
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'action' => $action,
                'status' => $status,
                'error_message' => $errorMessage,
                'payload' => $payload,
            ]);
        } catch (\Throwable $e) {
            Log::error('CrmGoogleSheetService: Failed to write sync log: ' . $e->getMessage());
        }
    }
}
