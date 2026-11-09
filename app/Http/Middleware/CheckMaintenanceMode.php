<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Profile;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next)
    {
        // Don't apply to admin routes or login/logout routes
        if ($request->is('admin*') || $request->is('login*')) {
            return $next($request);
        }

        // Fetch settings
        $settingsQuery = Setting::all()->pluck('value', 'key')->toArray();
        $settings = (object) $settingsQuery;

        $isMaintenance = isset($settings->maintenance_mode) && 
            in_array(strtolower((string)$settings->maintenance_mode), ['1', 'true', 'yes']);

        $isPreview = $request->query('preview') === '1';

        // Allow authenticated admins or explicitly previewing requests to bypass maintenance screen
        if ($isMaintenance && !$isPreview && !auth()->check()) {
            $profile = Profile::where('is_active', true)->first() ?? Profile::first();
            return response()->view('maintenance', compact('settings', 'profile'), 503);
        }

        return $next($request);
    }
}

