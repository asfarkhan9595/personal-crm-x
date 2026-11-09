<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeSwitchController extends Controller
{
    public function switch(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|string|in:theme1,theme3,nexus-glass,glass,modern',
        ]);

        $theme = strtolower($validated['theme']);

        $themeMap = [
            'theme1'      => 'theme1',
            'nexus-glass' => 'theme1',
            'nexus_glass' => 'theme1',
            'nexus'       => 'theme1',
            'modern'      => 'theme1',
            'theme3'      => 'theme3',
            'glass'       => 'theme3',
        ];

        $targetTheme = $themeMap[$theme] ?? 'theme1';
        $request->session()->put('active_theme', $targetTheme);
        \App\Models\Setting::updateOrCreate(
            ['key' => 'active_theme'],
            ['value' => $targetTheme, 'type' => 'text']
        );

        return response()->json([
            'success' => true,
            'theme' => $targetTheme,
            'message' => 'Theme updated successfully'
        ]);
    }
}
