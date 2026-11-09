<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50 dark:bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Portfolio</title>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-8">
        <!-- Logo / Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 mb-4">
                <i data-lucide="shield-check" class="w-6 h-6"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Dashboard</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Sign in to manage your portfolio</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 text-xs font-medium flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/20 text-xs">
                <div class="flex items-center gap-2 font-semibold mb-1">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-red-500"></i>
                    <span>Login Failed</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">Email Address</label>
                <div class="relative">
                    <input 
                        type="email" 
                        name="email" 
                        value="{{ old('email', 'admin@example.com') }}" 
                        required 
                        autofocus 
                        placeholder="admin@example.com"
                        class="w-full pl-10 pr-3.5 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                    >
                    <i data-lucide="mail" class="w-4 h-4 absolute left-3.5 top-3 text-gray-400"></i>
                </div>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">Password</label>
                <div class="relative">
                    <input 
                        type="password" 
                        name="password" 
                        required 
                        placeholder="••••••••"
                        class="w-full pl-10 pr-3.5 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                    >
                    <i data-lucide="lock" class="w-4 h-4 absolute left-3.5 top-3 text-gray-400"></i>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <span class="text-xs text-gray-600 dark:text-gray-400">Remember me</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-500/25 transition-all flex items-center justify-center gap-2"
            >
                <i data-lucide="log-in" class="w-4 h-4"></i>
                Sign In to Dashboard
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 text-center">
            <a href="http://localhost:5173" class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:underline flex items-center justify-center gap-1">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back to Main Portfolio Website
            </a>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>

