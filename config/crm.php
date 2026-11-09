<?php

return [
    /*
    |--------------------------------------------------------------------------
    | CRM Feature Flag
    |--------------------------------------------------------------------------
    | When false, all CRM routes are disabled and the sidebar section is hidden.
    */
    'enabled' => env('CRM_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    | Supported: INR, AED, USD
    */
    'default_currency' => env('CRM_DEFAULT_CURRENCY', 'INR'),

    /*
    |--------------------------------------------------------------------------
    | Google Sheets Integration
    |--------------------------------------------------------------------------
    */
    'google_sheet_id' => env('GOOGLE_SHEET_ID', ''),
    'google_service_account_json' => env('GOOGLE_SERVICE_ACCOUNT_JSON', ''),
    'google_sync_enabled' => env('CRM_GOOGLE_SYNC_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Exchange Rate API Key (Optional - free endpoints available without key)
    |--------------------------------------------------------------------------
    */
    'exchange_rate_api_key' => env('EXCHANGE_RATE_API_KEY', ''),
];
