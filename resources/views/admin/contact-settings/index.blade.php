@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                <i data-lucide="settings" class="w-7 h-7 text-indigo-600 dark:text-indigo-400"></i>
                Contact Settings & Visibility
            </h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Manage public contact methods (Email, WhatsApp), location, availability, and social link visibility toggles.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.social-links.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-100 text-xs font-semibold rounded-lg transition-colors">
                <i data-lucide="share-2" class="w-4 h-4 mr-1.5 text-indigo-500"></i> Manage Social Links Table
            </a>
            <a href="{{ route('admin.contact-messages.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm">
                <i data-lucide="mail" class="w-4 h-4 mr-1.5"></i> View Messages
            </a>
        </div>
    </div>

    <!-- Info Callout Banner for Clean Architecture -->
    <div class="p-4 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-200 dark:border-indigo-800 text-indigo-900 dark:text-indigo-200 text-xs flex items-start gap-3">
        <i data-lucide="info" class="w-5 h-5 text-indigo-600 dark:text-indigo-400 flex-shrink-0 mt-0.5"></i>
        <div>
            <span class="font-bold block mb-0.5">Social Profiles & Links Separation</span>
            <span>Social Profile URLs (GitHub, LinkedIn, Twitter/X, YouTube, etc.) are managed dynamically under the <a href="{{ route('admin.social-links.index') }}" class="underline font-bold text-indigo-600 dark:text-indigo-300">Social Links Table</a>. Below, you can configure main contact info and control platform visibility toggles across the portfolio.</span>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 text-xs flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
        <form action="{{ route('admin.contact-settings.update') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Section 1: Direct Contact Channels -->
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                <h3 class="text-base font-bold text-gray-900 dark:text-white uppercase tracking-wide flex items-center gap-2">
                    <i data-lucide="phone-call" class="w-4 h-4 text-indigo-600 dark:text-indigo-400"></i> Direct Contact Channels
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Primary communication parameters rendered in the Contact section.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Email Address -->
                <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/30">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-xs font-semibold text-gray-900 dark:text-white flex items-center gap-2 uppercase tracking-wider">
                            <i data-lucide="mail" class="w-4 h-4 text-indigo-500"></i> Email Address
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" name="show_email" value="1" {{ ($settings['show_email'] ?? '1') === '1' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="text-xs font-medium text-gray-600 dark:text-gray-300">Show Email</span>
                        </label>
                    </div>
                    <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? 'asfarkhan9595@gmail.com' }}" required placeholder="asfarkhan9595@gmail.com" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- WhatsApp Number -->
                <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/30">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-xs font-semibold text-gray-900 dark:text-white flex items-center gap-2 uppercase tracking-wider">
                            <i data-lucide="message-square" class="w-4 h-4 text-emerald-500"></i> WhatsApp Number
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" name="show_whatsapp" value="1" {{ ($settings['show_whatsapp'] ?? '1') === '1' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="text-xs font-medium text-gray-600 dark:text-gray-300">Show WhatsApp</span>
                        </label>
                    </div>
                    <input type="text" name="contact_whatsapp" value="{{ $settings['contact_whatsapp'] ?? '+919876543210' }}" placeholder="+919876543210" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 font-mono">
                </div>

                <!-- Location / Country -->
                <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/30">
                    <label class="block text-xs font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2 uppercase tracking-wider">
                        <i data-lucide="map-pin" class="w-4 h-4 text-red-500"></i> Location / Country
                    </label>
                    <input type="text" name="contact_location" value="{{ $settings['contact_location'] ?? 'India' }}" placeholder="India / Remote" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Availability Status -->
                <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/30">
                    <label class="block text-xs font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2 uppercase tracking-wider">
                        <i data-lucide="sparkles" class="w-4 h-4 text-amber-500"></i> Availability Status
                    </label>
                    <input type="text" name="contact_availability" value="{{ $settings['contact_availability'] ?? 'Open to opportunities' }}" placeholder="Open to full-time roles & freelance projects" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <!-- Section 2: Platform Visibility Controls -->
            <div class="pt-4 border-t border-gray-200 dark:border-gray-700 space-y-4">
                <div class="border-b border-gray-100 dark:border-gray-700 pb-3">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white uppercase tracking-wide flex items-center gap-2">
                        <i data-lucide="eye" class="w-4 h-4 text-indigo-600 dark:text-indigo-400"></i> Social Link Visibility Controls
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Toggle active visibility for social platforms rendered from your Social Links table.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <label class="p-3.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/30 flex items-center justify-between cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors">
                        <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                            <i data-lucide="github" class="w-4 h-4 text-slate-700 dark:text-slate-300"></i> Show GitHub
                        </span>
                        <input type="checkbox" name="show_github" value="1" {{ ($settings['show_github'] ?? '1') === '1' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    </label>

                    <label class="p-3.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/30 flex items-center justify-between cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors">
                        <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                            <i data-lucide="linkedin" class="w-4 h-4 text-blue-500"></i> Show LinkedIn
                        </span>
                        <input type="checkbox" name="show_linkedin" value="1" {{ ($settings['show_linkedin'] ?? '1') === '1' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    </label>

                    <label class="p-3.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/30 flex items-center justify-between cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors">
                        <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                            <i data-lucide="twitter" class="w-4 h-4 text-blue-400"></i> Show Twitter / X
                        </span>
                        <input type="checkbox" name="show_twitter" value="1" {{ ($settings['show_twitter'] ?? '1') === '1' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.social-links.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i> Edit Social Links URLs in Social Links Table
                </a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
