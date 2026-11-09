<!DOCTYPE html>
<html lang="en" class="scroll-smooth" data-theme="nexus-glass" data-default-mode="{{ $settings->default_theme ?? 'system' }}" data-enable-dark="{{ $settings->enable_dark_mode ?? 1 }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        (function() {
            const el = document.documentElement;
            const enableDark = el.getAttribute('data-enable-dark') !== '0';
            const defaultMode = el.getAttribute('data-default-mode') || 'system';
            const stored = localStorage.getItem('theme');
            if (enableDark) {
                if (stored === 'dark' || (!stored && defaultMode === 'dark') || (!stored && defaultMode === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    el.classList.add('dark');
                    el.setAttribute('data-mode', 'dark');
                } else {
                    el.classList.remove('dark');
                    el.setAttribute('data-mode', 'light');
                }
            }
        })();
    </script>
    <title>@yield('title', ($profile->name ?? 'Asfar Khan') . ' — Nexus Glass Portfolio')</title>
    <meta name="description" content="@yield('meta_description', $settings->site_description ?? ($profile->bio ?? 'Full-Stack Software Engineer & Python Specialist Portfolio'))">
    <link rel="icon" href="{{ format_image_url($settings->site_favicon ?? null, asset('favicon.ico')) }}">

    <!-- Open Graph SEO Meta -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', ($profile->name ?? 'Asfar Khan') . ' — Nexus Glass Portfolio')">
    <meta property="og:description" content="@yield('meta_description', $profile->bio ?? 'Full-Stack Software Engineer Portfolio')">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Twitter Card Meta -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', ($profile->name ?? 'Asfar Khan') . ' — Nexus Glass Portfolio')">
    <meta name="twitter:description" content="@yield('meta_description', $profile->bio ?? 'Full-Stack Software Engineer Portfolio')">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'system-ui', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    colors: {
                        nexus: {
                            50: '#f0f7ff',
                            100: '#e0effe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Custom App CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        :root {
            --nexus-primary: {{ $settings->primary_color ?? '#3b82f6' }};
        }

        /* Slim Glass Mesh Background */
        body {
            background-color: #f1f5f9;
            background-image: 
                radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.12) 0px, transparent 45%),
                radial-gradient(at 100% 0%, rgba(168, 85, 247, 0.12) 0px, transparent 45%),
                radial-gradient(at 100% 100%, rgba(236, 72, 153, 0.10) 0px, transparent 45%),
                radial-gradient(at 0% 100%, rgba(6, 182, 212, 0.12) 0px, transparent 45%);
            background-attachment: fixed;
        }

        .dark body {
            background-color: #030712;
            background-image: 
                radial-gradient(at 0% 0%, rgba(6, 182, 212, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(168, 85, 247, 0.15) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(59, 130, 246, 0.10) 0px, transparent 50%);
            background-attachment: fixed;
            color: #f8fafc;
        }

        /* Sleek Slim Glass Card (Non-bulky) */
        .nexus-glass-card {
            background: rgba(255, 255, 255, 0.45) !important;
            backdrop-filter: blur(16px) saturate(160%);
            -webkit-backdrop-filter: blur(16px) saturate(160%);
            border: 1px solid rgba(255, 255, 255, 0.6) !important;
            box-shadow: 0 4px 20px -4px rgba(31, 38, 135, 0.04);
        }

        .dark .nexus-glass-card {
            background: rgba(15, 23, 42, 0.45) !important;
            backdrop-filter: blur(16px) saturate(160%);
            -webkit-backdrop-filter: blur(16px) saturate(160%);
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 4px 20px -4px rgba(0, 0, 0, 0.4);
        }

        .nexus-glass-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px -6px rgba(59, 130, 246, 0.18) !important;
            border-color: rgba(59, 130, 246, 0.4) !important;
        }

        .dark .nexus-glass-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px -6px rgba(6, 182, 212, 0.18) !important;
            border-color: rgba(34, 211, 238, 0.3) !important;
        }
    </style>
</head>
<body class="min-h-screen font-sans selection:bg-blue-500/30 selection:text-blue-400 antialiased transition-colors duration-300 overflow-x-hidden">
    <!-- Unique Transparent Page Loader -->
    @include('partials.loader')
    
    <!-- Sticky Theme 1 Navbar -->
    @include('themes.theme1.components.navbar')

    <!-- Main View Content -->
    <main class="flex-1 pt-16 sm:pt-20">
        @yield('content')
    </main>

    <!-- Theme 1 Footer -->
    @include('themes.theme1.components.footer')

    <!-- Project Modal Component -->
    @include('partials.project-modal')

    <!-- App Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
