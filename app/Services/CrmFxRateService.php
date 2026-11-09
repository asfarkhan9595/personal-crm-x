<?php

namespace App\Services;

use App\Models\CrmFxRate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CrmFxRateService
{
    /**
     * Fallback rates to INR if APIs are unavailable
     */
    protected const FALLBACK_RATES = [
        'INR' => 1.0000,
        'AED' => 23.0000,
        'USD' => 84.0000,
    ];

    /**
     * Get the FX rate to INR for a given currency on a specific date (defaults to today).
     */
    public function getRate(string $currency, ?Carbon $date = null): float
    {
        $currency = strtoupper(trim($currency));
        if ($currency === 'INR') {
            return 1.0000;
        }

        $date = $date ? $date->copy()->startOfDay() : Carbon::today();
        $dateStr = $date->toDateString();

        // 1. Check database cache in crm_fx_rates
        $cached = CrmFxRate::where('from_currency', $currency)
            ->whereDate('fetched_on', $dateStr)
            ->first();

        if ($cached && $cached->rate > 0) {
            return (float) $cached->rate;
        }

        // 2. If looking for today's rate, fetch from free API
        if ($date->isToday()) {
            $fetchedRate = $this->fetchFromLiveApi($currency);
            if ($fetchedRate !== null && $fetchedRate > 0) {
                CrmFxRate::updateOrCreate(
                    ['from_currency' => $currency, 'fetched_on' => $dateStr],
                    ['rate' => $fetchedRate]
                );
                return $fetchedRate;
            }
        }

        // 3. Check for most recent rate available in DB
        $recent = CrmFxRate::where('from_currency', $currency)
            ->orderBy('fetched_on', 'desc')
            ->first();

        if ($recent && $recent->rate > 0) {
            return (float) $recent->rate;
        }

        // 4. Fallback rate
        return self::FALLBACK_RATES[$currency] ?? 1.0000;
    }

    /**
     * Fetch live rate to INR using free exchange rate APIs
     */
    protected function fetchFromLiveApi(string $currency): ?float
    {
        try {
            // Free API with no key required
            $response = Http::timeout(4)->get("https://open.er-api.com/v6/latest/{$currency}");
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['rates']['INR'])) {
                    return round((float) $data['rates']['INR'], 4);
                }
            }
        } catch (\Throwable $e) {
            Log::warning("FX Rate open.er-api.com fetch failed for {$currency}: " . $e->getMessage());
        }

        try {
            // Backup free API
            $response = Http::timeout(4)->get("https://api.exchangerate-api.com/v4/latest/{$currency}");
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['rates']['INR'])) {
                    return round((float) $data['rates']['INR'], 4);
                }
            }
        } catch (\Throwable $e) {
            Log::warning("FX Rate exchangerate-api.com fetch failed for {$currency}: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Manually set or update an FX rate for a specific date
     */
    public function setRate(string $currency, float $rate, ?Carbon $date = null): CrmFxRate
    {
        $currency = strtoupper(trim($currency));
        $date = $date ? $date->copy()->startOfDay() : Carbon::today();

        return CrmFxRate::updateOrCreate(
            ['from_currency' => $currency, 'fetched_on' => $date->toDateString()],
            ['rate' => round($rate, 4)]
        );
    }
}
