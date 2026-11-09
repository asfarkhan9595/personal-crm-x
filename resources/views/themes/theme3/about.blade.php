<section id="about" class="py-20 sm:py-24 relative bg-slate-200/40 dark:bg-slate-900/40 border-y border-slate-300/50 dark:border-white/10 backdrop-blur-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 dark:border-cyan-500/20 text-blue-600 dark:text-cyan-400 text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-blue-500 dark:bg-cyan-400 animate-pulse"></span>
                <span>About &amp; Overview</span>
            </span>
            <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-3">
                Background &amp; Philosophy
            </h2>
            <p class="mt-2 text-sm sm:text-base text-slate-600 dark:text-slate-300 max-w-xl mx-auto">
                Passionate engineer dedicated to crafting seamless web systems, backend APIs, and elegant UI interfaces.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
            
            <!-- Left Bio Card -->
            <div class="lg:col-span-7 flex flex-col">
                <div class="p-8 sm:p-10 rounded-3xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-2xl border border-white/80 dark:border-white/10 shadow-xl flex-1 flex flex-col justify-between space-y-6">
                    <div class="space-y-4">
                        <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white">
                            {{ $profile->about_title ?? 'Engineering Clean, Scalable Solutions' }}
                        </h3>
                        <div class="prose dark:prose-invert max-w-none text-sm text-slate-600 dark:text-slate-300 leading-relaxed space-y-3">
                            <p>
                                {{ $profile->about ?? $profile->bio ?? 'I am a passionate software developer with extensive experience building modern web applications, high-throughput microservices, and AI-driven automation systems.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Key Traits -->
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-200/60 dark:border-slate-800/60">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-blue-500/10 text-blue-600 dark:text-cyan-400 flex items-center justify-center font-bold text-base border border-blue-500/20">
                                ⚡
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 dark:text-white">High Performance</h4>
                                <p class="text-[11px] text-slate-500">Fast load times &amp; backend execution</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-blue-500/10 text-blue-600 dark:text-cyan-400 flex items-center justify-center font-bold text-base border border-blue-500/20">
                                🎨
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 dark:text-white">Clean UI / UX</h4>
                                <p class="text-[11px] text-slate-500">Frosted glass &amp; fluid interactions</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Stats Grid -->
            <div class="lg:col-span-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-6 rounded-3xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-2xl border border-white/80 dark:border-white/10 shadow-lg flex flex-col justify-between">
                    <span class="text-3xl font-extrabold text-blue-600 dark:text-cyan-400">
                        {{ $profile->years_experience ?? '3+' }}
                    </span>
                    <div class="mt-4">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white">Years Experience</h4>
                        <p class="text-[11px] text-slate-500 mt-1">Full-stack &amp; Python engineering</p>
                    </div>
                </div>

                <div class="p-6 rounded-3xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-2xl border border-white/80 dark:border-white/10 shadow-lg flex flex-col justify-between">
                    <span class="text-3xl font-extrabold text-blue-600 dark:text-cyan-400">
                        {{ !empty($profile->projects_count) ? $profile->projects_count : (($totalProjectsCount ?? count($projects)) . '+') }}
                    </span>
                    <div class="mt-4">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white">Projects Delivered</h4>
                        <p class="text-[11px] text-slate-500 mt-1">Production web systems &amp; tools</p>
                    </div>
                </div>

                <div class="p-6 rounded-3xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-2xl border border-white/80 dark:border-white/10 shadow-lg flex flex-col justify-between">
                    <span class="text-3xl font-extrabold text-blue-600 dark:text-cyan-400">
                        100%
                    </span>
                    <div class="mt-4">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white">Code Quality</h4>
                        <p class="text-[11px] text-slate-500 mt-1">Clean standards &amp; maintainability</p>
                    </div>
                </div>

                <div class="p-6 rounded-3xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-2xl border border-white/80 dark:border-white/10 shadow-lg flex flex-col justify-between">
                    <span class="text-3xl font-extrabold text-blue-600 dark:text-cyan-400">
                        24/7
                    </span>
                    <div class="mt-4">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white">Continuous Growth</h4>
                        <p class="text-[11px] text-slate-500 mt-1">Always mastering new frameworks</p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>
