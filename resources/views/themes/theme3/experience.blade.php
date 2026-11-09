<section id="experience" class="py-20 sm:py-24 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 dark:border-cyan-500/20 text-blue-600 dark:text-cyan-400 text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-blue-500 dark:bg-cyan-400 animate-pulse"></span>
                <span>Work History</span>
            </span>
            <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-3">
                Experience &amp; Career
            </h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300 max-w-xl mx-auto">
                Professional software development roles, engineering responsibilities, and achievements.
            </p>
        </div>

        @if(isset($experiences) && $experiences->count() > 0)
        <!-- Timeline Grid -->
        <div class="max-w-4xl mx-auto space-y-6">
            @foreach($experiences as $exp)
            <div class="p-6 sm:p-8 rounded-3xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-2xl border border-white/80 dark:border-white/10 shadow-lg space-y-4 transition-all duration-300 hover:border-blue-500/40">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <span class="px-3 py-1 rounded-full bg-blue-500/10 text-blue-600 dark:text-cyan-400 text-xs font-bold uppercase tracking-wider">
                            {{ $exp->company }}
                        </span>
                        <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 dark:text-white mt-2">
                            {{ $exp->title ?? $exp->position }}
                        </h3>
                    </div>
                    <span class="px-3.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-semibold self-start sm:self-auto">
                        {{ $exp->start_date }} — {{ $exp->end_date ?? ($exp->is_current ? 'Present' : '') }}
                    </span>
                </div>

                @if($exp->description)
                <div class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed space-y-2 pt-2 border-t border-slate-200/60 dark:border-slate-800/60">
                    {!! nl2br(e($exp->description)) !!}
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <!-- Fallback Default Card if DB Empty -->
        <div class="max-w-4xl mx-auto p-8 rounded-3xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-2xl border border-white/80 dark:border-white/10 shadow-lg space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <span class="px-3 py-1 rounded-full bg-blue-500/10 text-blue-600 dark:text-cyan-400 text-xs font-bold uppercase tracking-wider">
                        Software Engineering
                    </span>
                    <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 dark:text-white mt-2">
                        Senior Full-Stack &amp; Python Engineer
                    </h3>
                </div>
                <span class="px-3.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-semibold">
                    2021 — Present
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed pt-2 border-t border-slate-200/60 dark:border-slate-800/60">
                Architecting high-scale web platforms, FastAPI/Flask services, custom Web Scraping pipelines, and Laravel backend web architectures.
            </p>
        </div>
        @endif

    </div>
</section>
