<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $keys = [
            'site_name',
            'site_description',
            'site_url',
            'site_logo',
            'site_favicon',
            'default_theme',
            'active_theme',
            'site_theme_preset',
            'primary_color',
            'enable_dark_mode',
            'timezone',
            'maintenance_mode',
            'admin_email',
        ];

        $settings = Setting::whereIn('key', $keys)->pluck('value', 'key')->toArray();

        // Standard Defaults for the settings
        $defaults = [
            'site_name' => 'Asfar Khan — Portfolio',
            'site_description' => 'Asfar Khan is a Python & AI Automation Developer building practical AI applications, APIs, Chrome extensions, and automation tools.',
            'site_url' => 'http://localhost:5173',
            'site_logo' => '',
            'site_favicon' => '',
            'default_theme' => 'system',
            'active_theme' => 'modern',
            'active_theme' => 'theme1',
            'site_theme_preset' => 'classic',
            'primary_color' => '#10b981',
            'enable_dark_mode' => '1',
            'timezone' => 'Asia/Kolkata',
            'maintenance_mode' => '0',
            'admin_email' => 'asfarkhan9595@gmail.com',
        ];

        foreach ($defaults as $k => $v) {
            if (!isset($settings[$k])) {
                $settings[$k] = $v;
            }
        }

        // Fetch custom settings if any exist outside the main keys
        $customSettings = Setting::whereNotIn('key', $keys)->get();

        return view('admin.settings.index', compact('settings', 'customSettings'));
    }

    public function updateAll(Request $request)
    {
        $request->validate([
            'site_logo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'site_favicon_file' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,ico|max:2048',
            'active_theme' => 'nullable|in:theme1,theme3,nexus-glass,glass,modern',
        ]);

        $textFields = [
            'site_name' => 'text',
            'site_description' => 'textarea',
            'site_url' => 'text',
            'default_theme' => 'text',
            'active_theme' => 'text',
            'site_theme_preset' => 'text',
            'primary_color' => 'text',
            'timezone' => 'text',
            'admin_email' => 'text',
        ];

        foreach ($textFields as $field => $type) {
            if ($request->has($field)) {
                Setting::updateOrCreate(
                    ['key' => $field],
                    ['value' => $request->input($field), 'type' => $type]
                );
            }
        }

        if ($request->has('active_theme')) {
            $request->session()->forget('active_theme');
        }

        // Handle Site Logo File Upload or Direct Input
        if ($request->hasFile('site_logo_file')) {
            $existingLogo = Setting::where('key', 'site_logo')->value('value');
            delete_old_upload_file($existingLogo);

            $file = $request->file('site_logo_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            $logoPath = 'settings/' . $filename;
            Setting::updateOrCreate(
                ['key' => 'site_logo'],
                ['value' => $logoPath, 'type' => 'text']
            );
        } elseif ($request->has('site_logo')) {
            Setting::updateOrCreate(
                ['key' => 'site_logo'],
                ['value' => $request->input('site_logo'), 'type' => 'text']
            );
        }

        // Handle Site Favicon File Upload or Direct Input
        if ($request->hasFile('site_favicon_file')) {
            $existingFavicon = Setting::where('key', 'site_favicon')->value('value');
            delete_old_upload_file($existingFavicon);

            $file = $request->file('site_favicon_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            $faviconPath = 'settings/' . $filename;
            Setting::updateOrCreate(
                ['key' => 'site_favicon'],
                ['value' => $faviconPath, 'type' => 'text']
            );
        } elseif ($request->has('site_favicon')) {
            Setting::updateOrCreate(
                ['key' => 'site_favicon'],
                ['value' => $request->input('site_favicon'), 'type' => 'text']
            );
        }

        // Checkboxes / Toggles
        $checkboxes = [
            'enable_dark_mode',
            'maintenance_mode',
        ];

        foreach ($checkboxes as $box) {
            $val = $request->has($box) ? '1' : '0';
            Setting::updateOrCreate(
                ['key' => $box],
                ['value' => $val, 'type' => 'boolean']
            );
        }

        return redirect()->route('admin.settings.index')->with('success', 'Website settings updated successfully.');
    }

    public function storeCustom(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:settings,key',
            'value' => 'nullable|string',
            'type' => 'required|string|in:text,boolean,number,textarea,json',
        ]);

        Setting::create($validated);

        return redirect()->route('admin.settings.index')->with('success', "Custom setting '{$validated['key']}' created successfully.");
    }

    public function destroy($id)
    {
        $setting = Setting::findOrFail($id);
        $key = $setting->key;
        $setting->delete();

        return redirect()->route('admin.settings.index')->with('success', "Custom setting '{$key}' deleted successfully.");
    }
}
