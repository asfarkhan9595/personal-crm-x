<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactFrontendController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $message = ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'] ?? 'Website Inquiry',
            'message' => $validated['message'],
            'status' => 'unread',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for your message! I will get back to you soon.'
            ]);
        }

        return back()->with('success', 'Thank you for your message! I will get back to you soon.');
    }
}

