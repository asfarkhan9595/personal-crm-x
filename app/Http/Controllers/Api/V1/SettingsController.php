<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Http\Resources\SettingResource;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all();

        return response()->json([
            'success' => true,
            'data' => SettingResource::collection($settings)
        ]);
    }
}

