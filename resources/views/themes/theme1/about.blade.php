<section id="about" class="py-12 sm:py-16 relative">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-8">
            <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 dark:border-cyan-500/20 text-blue-600 dark:text-cyan-400 text-[11px] font-semibold uppercase tracking-wider backdrop-blur-md">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 dark:bg-cyan-400 animate-pulse"></span>
                <span>Background</span>
            </div>
            <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-2">
                About Me
            </h2>
        </div>

        <!-- Sleek Glass About Card -->
        <div class="p-6 sm:p-8 rounded-2xl nexus-glass-card">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                
                <div class="lg:col-span-8 space-y-3">
                    <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white">
                        {{ $profile->about_title ?? 'Crafting High-Performance Backend & Web Systems' }}
                    </h3>
                    <p class="text-xs sm:text-sm leading-relaxed text-slate-600 dark:text-slate-300 font-normal">
                        {{ $profile->about ?? $profile->bio ?? 'Full-stack software developer with deep expertise in Python REST APIs, microservices, Laravel web platforms, Chrome extensions, and automated scraping pipelines.' }}
                    </p>

                    <div class="pt-2 grid grid-cols-3 gap-3 font-mono text-xs">
                        <div class="p-3 rounded-xl bg-white/40 dark:bg-slate-900/40 border border-white/50 dark:border-white/10">
                            <span class="text-lg font-black text-blue-600 dark:text-cyan-400 font-sans block">{{ $profile->years_experience ?? '3+' }}</span>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400">Experience</span>
                        </div>
                        <div class="p-3 rounded-xl bg-white/40 dark:bg-slate-900/40 border border-white/50 dark:border-white/10">
                            <span class="text-lg font-black text-indigo-600 dark:text-purple-400 font-sans block">{{ !empty($profile->projects_count) ? $profile->projects_count : (($totalProjectsCount ?? count($projects)) . '+') }}</span>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400">Projects</span>
                        </div>
                        <div class="p-3 rounded-xl bg-white/40 dark:bg-slate-900/40 border border-white/50 dark:border-white/10">
                            <span class="text-lg font-black text-emerald-600 dark:text-emerald-400 font-sans block">{{ count($skillCategories) }}</span>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400">Domains</span>
                        </div>
                    </div>
                </div>

                {{-- Highlights Card --}}
                <div class="lg:col-span-4 p-4 rounded-xl bg-white/40 dark:bg-slate-900/40 border border-white/50 dark:border-white/10 space-y-2">
                    <h4 class="text-[10px] font-mono font-bold uppercase tracking-wider text-blue-600 dark:text-cyan-400">// HIGHLIGHTS</h4>
                    <ul class="space-y-2 text-xs text-slate-700 dark:text-slate-300">
                        @if(!empty($profile->about_highlights))
                            @foreach((array)$profile->about_highlights as $highlight)
                                <li class="flex items-start gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    <span>{{ $highlight }}</span>
                                </li>
                            @endforeach
                        @else
                            <li class="flex items-start gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                <span>Laravel Blade Server Rendering</span>
                            </li>
                            <li class="flex items-start gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                <span>Python, FastAPI &amp; AI Workflows</span>
                            </li>
                        @endif
                    </ul>
                </div>

            </div>
        </div>

    </div>
</section>
