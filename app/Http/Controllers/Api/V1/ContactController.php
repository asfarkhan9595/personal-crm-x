<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\ContactSetting;
use App\Mail\ContactMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        $validated['status'] = 'new';
        $validated['is_read'] = false;
        $validated['ip_address'] = $request->ip();
        $validated['user_agent'] = substr((string)$request->header('User-Agent'), 0, 500);

        $message = ContactMessage::create($validated);

        // Attempt to send notification email
        try {
            $dbAdminEmail = \App\Models\Setting::where('key', 'admin_email')->value('value');
            $adminEmail = $dbAdminEmail ?: env('ADMIN_NOTIFICATION_EMAIL', config('mail.from.address', 'asfarkhan9595@gmail.com'));
            Mail::to($adminEmail)->send(new ContactMessageNotification($message));
        } catch (\Exception $e) {
            Log::error('Failed to send contact notification email: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent successfully.',
            'data' => $message
        ], 201);
    }

    public function settings()
    {
        $keys = [
            'contact_email',
            'contact_whatsapp',
            'contact_linkedin',
            'contact_github',
            'contact_twitter',
            'contact_location',
            'contact_availability',
            'show_email',
            'show_whatsapp',
            'show_linkedin',
            'show_github',
            'show_twitter',
        ];

        $settings = ContactSetting::whereIn('key', $keys)->pluck('value', 'key')->toArray();

        return response()->json([
            'success' => true,
            'data' => [
                'email' => $settings['contact_email'] ?? 'asfarkhan9595@gmail.com',
                'whatsapp' => $settings['contact_whatsapp'] ?? '+919129599595',
                'linkedin' => $settings['contact_linkedin'] ?? 'https://www.linkedin.com/in/asfar-khan-ai/',
                'github' => $settings['contact_github'] ?? 'https://github.com/asfarkhan9595',
                'twitter' => $settings['contact_twitter'] ?? 'https://x.com/asfarkhan9595',
                'location' => $settings['contact_location'] ?? 'India',
                'availability' => $settings['contact_availability'] ?? 'Open to opportunities',
                'visibility' => [
                    'show_email' => ($settings['show_email'] ?? '1') === '1',
                    'show_whatsapp' => ($settings['show_whatsapp'] ?? '1') === '1',
                    'show_linkedin' => ($settings['show_linkedin'] ?? '1') === '1',
                    'show_github' => ($settings['show_github'] ?? '1') === '1',
                    'show_twitter' => ($settings['show_twitter'] ?? '1') === '1',
                ]
            ]
        ]);
    }
}
