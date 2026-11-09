<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-900 text-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="h-full flex items-center justify-center p-6 font-sans">
    <div class="max-w-md w-full text-center space-y-6">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-400 mb-2">
            <i data-lucide="alert-triangle" class="w-10 h-10 animate-bounce"></i>
        </div>

        <div class="space-y-2">
            <h1 class="text-6xl font-extrabold tracking-tight text-red-500 font-mono">500</h1>
            <h2 class="text-2xl font-bold text-gray-100">Server Error</h2>
            <p class="text-sm text-gray-400 max-w-sm mx-auto leading-relaxed">
                Something went wrong on our end. Please refresh the page or try again in a few moments.
            </p>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-3">
            <button onclick="window.location.reload()" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white text-sm font-semibold shadow-lg shadow-red-500/20 transition-all flex items-center justify-center gap-2">
                <i data-lucide="rotate-cw" class="w-4 h-4"></i>
                Reload Page
            </button>
            <a href="{{ url('/') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-gray-700 bg-gray-800 hover:bg-gray-700 text-gray-300 text-sm font-medium transition-colors flex items-center justify-center gap-2">
                <i data-lucide="home" class="w-4 h-4"></i>
                Return Home
            </a>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>

