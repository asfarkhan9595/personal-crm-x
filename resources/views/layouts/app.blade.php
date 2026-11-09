<!DOCTYPE html>
<html lang="en" class="scroll-smooth" data-theme="{{ $settings->active_theme ?? 'modern' }}" data-default-mode="{{ $settings->default_theme ?? 'system' }}" data-enable-dark="{{ $settings->enable_dark_mode ?? 1 }}">
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
    <title>@yield('title', $settings->site_name ?? ($profile->name ?? 'Portfolio'))</title>
    <meta name="description" content="@yield('meta_description', $settings->site_description ?? ($profile->bio ?? 'Full-Stack & Python Developer Portfolio'))">
    <link rel="icon" href="{{ format_image_url($settings->site_favicon ?? null, asset('favicon.ico')) }}">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        emerald: {
                            500: 'var(--primary-color, #10b981)',
                            600: 'var(--primary-color, #059669)',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        :root {
            --primary-color: {{ $settings->primary_color ?? '#10b981' }};
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-sans transition-colors duration-300">
    <!-- Unique Transparent Page Loader -->
    @include('partials.loader')
    
    <!-- Navigation -->
    @include('partials.navbar')

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Project Modal Partial -->
    @include('partials.project-modal')

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>

