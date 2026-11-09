<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-900 text-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Page Expired</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="h-full flex items-center justify-center p-6 font-sans">
    <div class="max-w-md w-full text-center space-y-6">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 mb-2">
            <i data-lucide="clock" class="w-10 h-10 animate-spin"></i>
        </div>

        <div class="space-y-2">
            <h1 class="text-6xl font-extrabold tracking-tight text-cyan-500 font-mono">419</h1>
            <h2 class="text-2xl font-bold text-gray-100">Page Expired</h2>
            <p class="text-sm text-gray-400 max-w-sm mx-auto leading-relaxed">
                Your session has expired due to inactivity. Please refresh the page and try again.
            </p>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-3">
            <button onclick="window.location.reload()" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white text-sm font-semibold shadow-lg shadow-cyan-500/20 transition-all flex items-center justify-center gap-2">
                <i data-lucide="rotate-cw" class="w-4 h-4"></i>
                Refresh Page
            </button>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>

