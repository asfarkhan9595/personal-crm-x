@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Top Header -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                <i data-lucide="settings" class="w-7 h-7 text-indigo-600 dark:text-indigo-400"></i>
                Main Website Settings
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Configure global portfolio brand details, theme appearance, system timezones, and site behaviors.</p>
        </div>
        <button onclick="document.getElementById('addCustomSettingModal').classList.remove('hidden')" type="button" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 text-sm font-medium rounded-lg transition-colors border border-gray-200 dark:border-gray-600">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add Custom Setting
        </button>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/20">
            <div class="flex items-center gap-3 mb-1">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                <span class="font-semibold">Please fix the following errors:</span>
            </div>
            <ul class="list-disc list-inside text-xs pl-8 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Settings Form -->
    <form action="{{ route('admin.settings.update-all') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- 1. 🌐 Website Settings Group -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
            <div class="flex items-center gap-3 pb-4 border-b border-gray-200 dark:border-gray-700 mb-6">
                <div class="w-9 h-9 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                    <i data-lucide="globe" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Website & Branding</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Configure global website titles, description, logo, and favicon.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Site Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">Site Name *</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] ?? '' }}" required placeholder="e.g. Asfar Khan — Portfolio" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Site URL -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">Site URL</label>
                    <input type="url" name="site_url" value="{{ $settings['site_url'] ?? '' }}" placeholder="http://localhost:5173 or https://yourdomain.com" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Site Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">Site Description (SEO & Meta)</label>
                    <textarea name="site_description" rows="2" placeholder="Brief summary of your portfolio for search engines..." class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">{{ $settings['site_description'] ?? '' }}</textarea>
                </div>

                <!-- Site Logo Upload -->
                <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2 flex items-center justify-between">
                        <span>Site Logo Image</span>
                        <span class="text-xs font-normal text-gray-500">PNG, JPG, SVG, WebP (Max 2MB)</span>
                    </label>

                    @if(!empty($settings['site_logo']))
                        <div class="mb-3 flex items-center gap-3 p-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                            <img src="{{ format_image_url($settings['site_logo']) }}" alt="Current Logo" class="w-10 h-10 object-contain rounded-md border border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 p-1">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-900 dark:text-white truncate">Current Logo Active</p>
                                <p class="text-[10px] text-gray-500 truncate">{{ $settings['site_logo'] }}</p>
                            </div>
                        </div>
                    @endif

                    <input type="file" name="site_logo_file" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-950 dark:file:text-indigo-300">
                    <input type="text" name="site_logo" value="{{ $settings['site_logo'] ?? '' }}" placeholder="Or paste image URL..." class="w-full mt-2 px-3 py-1.5 text-xs rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>

                <!-- Site Favicon Upload -->
                <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2 flex items-center justify-between">
                        <span>Favicon Icon Image</span>
                        <span class="text-xs font-normal text-gray-500">ICO, PNG, SVG (Max 2MB)</span>
                    </label>

                    @if(!empty($settings['site_favicon']))
                        <div class="mb-3 flex items-center gap-3 p-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                            <img src="{{ format_image_url($settings['site_favicon']) }}" alt="Current Favicon" class="w-8 h-8 object-contain rounded-md border border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 p-1">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-900 dark:text-white truncate">Current Favicon Active</p>
                                <p class="text-[10px] text-gray-500 truncate">{{ $settings['site_favicon'] }}</p>
                            </div>
                        </div>
                    @endif

                    <input type="file" name="site_favicon_file" accept="image/*,.ico" class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-950 dark:file:text-indigo-300">
                    <input type="text" name="site_favicon" value="{{ $settings['site_favicon'] ?? '' }}" placeholder="Or paste icon URL..." class="w-full mt-2 px-3 py-1.5 text-xs rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>
        </div>

        @php
            $rawDbTheme = strtolower((string)($settings['active_theme'] ?? 'theme1'));
            if (in_array($rawDbTheme, ['theme3', 'glass'])) {
                $currentAdminThemeKey = 'theme3';
                $currentAdminThemeLabel = 'Theme 3 (Glass)';
            } else {
                $currentAdminThemeKey = 'theme1';
                $currentAdminThemeLabel = 'Theme 1 (Nexus Glass)';
            }
        @endphp

        <!-- 1. 🎨 Portfolio Theme Manager Group -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
            <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-gray-700 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                        <i data-lucide="layout" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Portfolio Theme Manager</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Select the default UI Design System for all visitors. Visitors can also switch themes on the frontend.</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-950 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                    Active: {{ $currentAdminThemeLabel }}
                </span>
            </div>

            <input type="hidden" name="active_theme" id="active_theme_input" value="{{ $currentAdminThemeKey }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- 1. Theme 1 (Nexus Glass) Theme Card -->
                <div onclick="selectTheme('theme1')" id="theme-card-theme1" class="group relative rounded-2xl border-2 p-5 cursor-pointer transition-all duration-200 {{ $currentAdminThemeKey === 'theme1' ? 'border-indigo-600 bg-indigo-50/40 dark:bg-indigo-950/20 dark:border-indigo-500 shadow-md' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 bg-white dark:bg-gray-800' }}">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="text-base font-bold text-gray-900 dark:text-white">Theme 1 (Nexus Glass)</span>
                            <span class="text-[10px] px-2 py-0.5 rounded bg-blue-100 dark:bg-cyan-950 text-blue-600 dark:text-cyan-400 font-semibold">Cyber Glass</span>
                        </div>
                        <div id="badge-theme1" class="{{ $currentAdminThemeKey === 'theme1' ? 'flex' : 'hidden' }} items-center gap-1 text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                            <i data-lucide="check-circle" class="w-4 h-4"></i> Active
                        </div>
                    </div>
                    
                    <!-- Preview mockup thumbnail -->
                    <div class="h-28 rounded-xl bg-gradient-to-br from-slate-950 via-indigo-950 to-purple-950 p-3 mb-3 border border-cyan-500/30 flex flex-col justify-between overflow-hidden relative">
                        <div class="absolute -top-4 -right-4 w-16 h-16 bg-cyan-500/20 rounded-full blur-lg"></div>
                        <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-blue-500/20 rounded-full blur-lg"></div>

                        <div class="flex items-center justify-between border-b border-white/10 backdrop-blur-md pb-2 relative z-10">
                            <div class="h-2 w-12 bg-cyan-400 rounded"></div>
                            <div class="flex gap-1">
                                <div class="h-2 w-4 bg-white/20 rounded"></div>
                                <div class="h-2 w-4 bg-white/20 rounded"></div>
                            </div>
                        </div>
                        <div class="space-y-1.5 my-auto backdrop-blur-sm p-1.5 bg-white/5 rounded-md border border-white/10 relative z-10">
                            <div class="h-2.5 w-3/4 bg-gradient-to-r from-cyan-400 to-blue-500 rounded"></div>
                            <div class="h-2 w-1/2 bg-slate-300/60 rounded"></div>
                        </div>
                        <div class="flex gap-2 relative z-10">
                            <div class="h-5 w-16 bg-gradient-to-r from-cyan-500 to-blue-600 rounded text-[8px] text-white flex items-center justify-center font-bold shadow">Theme 1</div>
                            <div class="h-5 w-14 bg-white/10 backdrop-blur-md rounded border border-white/20 text-[8px] text-cyan-200 flex items-center justify-center">Nexus Glass</div>
                        </div>
                    </div>

                    <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed mb-4">
                        Flagship cybernetic glassmorphism design system featuring floating glass cards, backdrop blur, ambient neon glows, and custom AJAX controls.
                    </p>

                    @if($currentAdminThemeKey === 'theme1')
                        <button type="button" class="w-full py-2 px-3 text-xs font-semibold rounded-xl bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800 flex items-center justify-center gap-1.5 cursor-default">
                            <i data-lucide="check" class="w-4 h-4 text-emerald-600"></i> Currently Active Theme
                        </button>
                    @else
                        <button type="button" onclick="selectTheme('theme1'); event.stopPropagation();" class="w-full py-2 px-3 text-xs font-semibold rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm transition-colors flex items-center justify-center gap-1.5">
                            <i data-lucide="sparkles" class="w-4 h-4"></i> Activate Theme 1 (Nexus Glass)
                        </button>
                    @endif
                </div>

                <!-- 2. Theme 3 (Glass UI) Card -->
                <div onclick="selectTheme('theme3')" id="theme-card-theme3" class="group relative rounded-2xl border-2 p-5 cursor-pointer transition-all duration-200 {{ $currentAdminThemeKey === 'theme3' ? 'border-indigo-600 bg-indigo-50/40 dark:bg-indigo-950/20 dark:border-indigo-500 shadow-md' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 bg-white dark:bg-gray-800' }}">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="text-base font-bold text-gray-900 dark:text-white">Theme 3 (Glass)</span>
                            <span class="text-[10px] px-2 py-0.5 rounded bg-purple-100 dark:bg-purple-950 text-purple-600 dark:text-purple-300 font-semibold">Frosted & Glow</span>
                        </div>
                        <div id="badge-theme3" class="{{ $currentAdminThemeKey === 'theme3' ? 'flex' : 'hidden' }} items-center gap-1 text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                            <i data-lucide="check-circle" class="w-4 h-4"></i> Active
                        </div>
                    </div>

                    <!-- Preview mockup thumbnail -->
                    <div class="h-28 rounded-xl bg-slate-900 p-3 mb-3 border border-slate-700/50 flex flex-col justify-between overflow-hidden relative">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                            <div class="h-2 w-12 bg-blue-500 rounded"></div>
                            <div class="flex gap-1">
                                <div class="h-2 w-4 bg-slate-700 rounded"></div>
                                <div class="h-2 w-4 bg-slate-700 rounded"></div>
                            </div>
                        </div>
                        <div class="space-y-1.5 my-auto">
                            <div class="h-2.5 w-3/4 bg-white rounded font-bold"></div>
                            <div class="h-2 w-1/2 bg-slate-400 rounded"></div>
                        </div>
                        <div class="flex gap-2">
                            <div class="h-5 w-16 bg-blue-600 rounded text-[8px] text-white flex items-center justify-center font-bold">Theme 3</div>
                            <div class="h-5 w-14 bg-slate-800 rounded border border-slate-700 text-[8px] text-slate-300 flex items-center justify-center">Glass</div>
                        </div>
                    </div>

                    <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed mb-4">
                        Clean translucent design system featuring rounded glass panels, soft backdrop blurs, and minimalist section hierarchy.
                    </p>

                    @if($currentAdminThemeKey === 'theme3')
                        <button type="button" class="w-full py-2 px-3 text-xs font-semibold rounded-xl bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800 flex items-center justify-center gap-1.5 cursor-default">
                            <i data-lucide="check" class="w-4 h-4 text-emerald-600"></i> Currently Active Theme
                        </button>
                    @else
                        <button type="button" onclick="selectTheme('theme3'); event.stopPropagation();" class="w-full py-2 px-3 text-xs font-semibold rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm transition-colors flex items-center justify-center gap-1.5">
                            <i data-lucide="zap" class="w-4 h-4"></i> Activate Theme 3 (Glass)
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <script>
            function selectTheme(themeKey) {
                const input = document.getElementById('active_theme_input');
                if (input) {
                    input.value = themeKey;
                    const form = input.closest('form');
                    if (form) {
                        form.submit();
                    }
                }
            }
        </script>

        <!-- 2. 🎨 Appearance Group -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
            <div class="flex items-center gap-3 pb-4 border-b border-gray-200 dark:border-gray-700 mb-6">
                <div class="w-9 h-9 rounded-lg bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                    <i data-lucide="palette" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Theme & Appearance</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Control default theme mode (Light/Dark), primary color palette, and visitor dark mode toggle.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">


                <!-- Default Theme -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">Default Theme Mode</label>
                    <select name="default_theme" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="system" {{ ($settings['default_theme'] ?? 'system') == 'system' ? 'selected' : '' }}>System (Auto)</option>
                        <option value="dark" {{ ($settings['default_theme'] ?? '') == 'dark' ? 'selected' : '' }}>Dark Theme</option>
                        <option value="light" {{ ($settings['default_theme'] ?? '') == 'light' ? 'selected' : '' }}>Light Theme</option>
                    </select>
                </div>

                <!-- Primary Color -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-sm font-semibold text-gray-900 dark:text-white">Primary Color Accent</label>
                        <button type="button" onclick="document.getElementById('primary_color_picker').value='#10b981'; document.getElementById('primary_color_input').value='#10b981';" class="text-[11px] font-medium text-indigo-600 dark:text-indigo-400 hover:underline">Reset to Default (#10b981)</button>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="color" id="primary_color_picker" value="{{ $settings['primary_color'] ?? '#10b981' }}" onchange="document.getElementById('primary_color_input').value = this.value" class="h-10 w-12 rounded border border-gray-300 dark:border-gray-600 cursor-pointer bg-white dark:bg-gray-700 p-1">
                        <input type="text" name="primary_color" id="primary_color_input" value="{{ $settings['primary_color'] ?? '#10b981' }}" onchange="document.getElementById('primary_color_picker').value = this.value" class="w-full px-3.5 py-2.5 text-sm font-mono rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <!-- Enable Dark Mode Toggle -->
                <div class="flex flex-col justify-center">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">Dark Mode Option</label>
                    <label class="inline-flex items-center gap-2 mt-2 cursor-pointer">
                        <input type="checkbox" name="enable_dark_mode" value="1" {{ ($settings['enable_dark_mode'] ?? '1') === '1' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Enable Dark Mode Toggle on Site</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- 3. 📅 System & Admin Group -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
            <div class="flex items-center gap-3 pb-4 border-b border-gray-200 dark:border-gray-700 mb-6">
                <div class="w-9 h-9 rounded-lg bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                    <i data-lucide="shield" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">System & Maintenance</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Configure timezone, admin alert email, and site maintenance status.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Timezone -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">Timezone</label>
                    <input type="text" name="timezone" value="{{ $settings['timezone'] ?? 'Asia/Kolkata' }}" placeholder="Asia/Kolkata" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Admin Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">Admin Alert Email</label>
                    <input type="email" name="admin_email" value="{{ $settings['admin_email'] ?? 'asfarkhan9595@gmail.com' }}" placeholder="asfarkhan9595@gmail.com" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Maintenance Mode Toggle -->
                <div class="flex flex-col justify-center">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">Maintenance Mode</label>
                    <label class="inline-flex items-center gap-2 mt-2 cursor-pointer">
                        <input type="checkbox" name="maintenance_mode" value="1" {{ ($settings['maintenance_mode'] ?? '0') === '1' ? 'checked' : '' }} class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <span class="text-sm font-medium text-red-600 dark:text-red-400 font-bold">Turn Maintenance Mode ON</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Submit Button Row -->
        <div class="flex justify-end pt-2">
            <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-500/25 transition-all flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i> Save Main Settings
            </button>
        </div>
    </form>

    <!-- Custom Settings List (If any exist) -->
    @if(isset($customSettings) && $customSettings->count() > 0)
    <div class="mt-12 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Custom Configuration Keys</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                        <th class="px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Key</th>
                        <th class="px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Value</th>
                        <th class="px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Type</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 font-mono text-xs">
                    @foreach($customSettings as $custom)
                    <tr>
                        <td class="px-4 py-3 font-bold text-indigo-600 dark:text-indigo-400">{{ $custom->key }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300 max-w-xs truncate">{{ $custom->value }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ strtoupper($custom->type) }}</td>
                        <td class="px-4 py-3 text-right">
                            <form action="{{ route('admin.settings.destroy', $custom->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 text-red-500 hover:text-red-700" title="Delete">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Add Custom Setting Modal -->
    <div id="addCustomSettingModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div onclick="document.getElementById('addCustomSettingModal').classList.add('hidden')" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative z-10 w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Add Custom Setting Key</h3>
                <button onclick="document.getElementById('addCustomSettingModal').classList.add('hidden')" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('admin.settings.store-custom') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Setting Key *</label>
                    <input type="text" name="key" required placeholder="e.g. custom_header_code" class="w-full px-3.5 py-2.5 text-sm font-mono rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Type *</label>
                    <select name="type" required class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="text">Text (string)</option>
                        <option value="textarea">Textarea (multiline)</option>
                        <option value="boolean">Boolean (0 or 1)</option>
                        <option value="number">Number</option>
                        <option value="json">JSON</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Setting Value</label>
                    <textarea name="value" rows="3" placeholder="Enter custom value..." class="w-full px-3.5 py-2.5 text-sm font-mono rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button onclick="document.getElementById('addCustomSettingModal').classList.add('hidden')" type="button" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-md shadow-indigo-500/20">
                        Save Custom Setting
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection