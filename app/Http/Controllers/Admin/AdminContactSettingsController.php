<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSetting;
use Illuminate\Http\Request;

class AdminContactSettingsController extends Controller
{
    public function index()
    {
        $keys = [
            'contact_email',
            'contact_whatsapp',
            'contact_location',
            'contact_availability',
            'show_email',
            'show_whatsapp',
            'show_linkedin',
            'show_github',
            'show_twitter',
        ];

        $settings = ContactSetting::whereIn('key', $keys)->pluck('value', 'key')->toArray();

        // Defaults
        $defaults = [
            'contact_email' => 'asfarkhan9595@gmail.com',
            'contact_whatsapp' => '+919129599595',
            'contact_location' => 'India',
            'contact_availability' => 'Open to opportunities',
            'show_email' => '1',
            'show_whatsapp' => '1',
            'show_linkedin' => '1',
            'show_github' => '1',
            'show_twitter' => '1',
        ];

        foreach ($defaults as $k => $v) {
            if (!isset($settings[$k])) {
                $settings[$k] = $v;
            }
        }

        return view('admin.contact-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $fields = [
            'contact_email',
            'contact_whatsapp',
            'contact_location',
            'contact_availability',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                ContactSetting::updateOrCreate(
                    ['key' => $field],
                    ['value' => $request->input($field), 'type' => 'text']
                );
            }
        }

        $checkboxes = [
            'show_email',
            'show_whatsapp',
            'show_linkedin',
            'show_github',
            'show_twitter',
        ];

        foreach ($checkboxes as $box) {
            $val = $request->has($box) ? '1' : '0';
            ContactSetting::updateOrCreate(
                ['key' => $box],
                ['value' => $val, 'type' => 'boolean']
            );
        }

        return redirect()->route('admin.contact-settings.index')->with('success', 'Contact settings updated successfully.');
    }
}
