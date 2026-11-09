<footer class="bg-white dark:bg-slate-900 border-t border-slate-200/50 dark:border-slate-800/50 py-12 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-tr from-emerald-500 to-teal-400 text-white font-bold text-base shadow">
                    {{ strtoupper(substr($profile->name ?? 'A', 0, 1)) }}
                </span>
                <span class="font-bold text-slate-800 dark:text-slate-200">
                    {{ $profile->name ?? 'Portfolio' }}
                </span>
            </div>

            <p class="text-xs text-slate-500 dark:text-slate-400 text-center">
                © {{ date('Y') }} {{ $profile->name ?? 'Portfolio' }}. Built with Laravel & Blade (No React / API runtime).
            </p>

            <div class="flex items-center gap-4 text-xs text-slate-500">
                <a href="#home" class="hover:text-emerald-500 transition-colors">Back to Top ↑</a>
            </div>
        </div>
    </div>
</footer>

