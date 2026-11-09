<section id="skills" class="py-20 relative bg-white dark:bg-slate-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-14">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-xs font-bold uppercase tracking-widest">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                My Capabilities
            </div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 mt-3 sm:text-4xl tracking-tight">
                Skills & Technologies
            </h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 max-w-xl mx-auto">
                Comprehensive tech stack across full-stack web development, AI APIs, browser automation, and DevOps.
            </p>
        </div>

        <!-- Sleek Skills Category Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($skillCategories as $cat)
            @php
                $slug = \Illuminate\Support\Str::slug($cat->name);
                $isAi = str_contains($slug, 'ai');
                $isBackend = str_contains($slug, 'backend');
                $isProg = str_contains($slug, 'programming') || str_contains($slug, 'language');
                $isTools = str_contains($slug, 'tool') || str_contains($slug, 'devops');
            @endphp
            <div class="group relative p-5 sm:p-6 rounded-2xl bg-slate-50/70 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800/80 shadow-sm hover:shadow-xl hover:border-emerald-500/40 dark:hover:border-emerald-500/40 transition-all duration-300">
                <!-- Top Accent Line on Hover -->
                <div class="absolute top-0 left-6 right-6 h-0.5 bg-gradient-to-r from-emerald-500 to-teal-400 opacity-0 group-hover:opacity-100 transition-opacity rounded-full"></div>

                <!-- Category Header -->
                <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-200/60 dark:border-slate-800/80">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                            @if($isAi)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            @elseif($isBackend)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"></path></svg>
                            @elseif($isProg)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                            @elseif($isTools)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a2 2 0 01-2 2 2 2 0 01-2-2V4zm-6 8a2 2 0 114 0v1a2 2 0 01-2 2 2 2 0 01-2-2v-1z"></path></svg>
                            @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            @endif
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 group-hover:text-emerald-500 transition-colors">
                            {{ $cat->name }}
                        </h3>
                    </div>

                    <span class="text-[11px] font-mono font-medium text-slate-400 dark:text-slate-500">
                        {{ $cat->skills->count() }} {{ \Illuminate\Support\Str::plural('skill', $cat->skills->count()) }}
                    </span>
                </div>

                <!-- Skill Pills -->
                <div class="flex flex-wrap gap-2">
                    @foreach($cat->skills as $skill)
                    <div class="skill-tag inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-white dark:bg-slate-800/90 text-slate-700 dark:text-slate-200 border border-slate-200/80 dark:border-slate-700/70 hover:border-emerald-500/50 hover:bg-emerald-500/10 hover:text-emerald-600 dark:hover:text-emerald-400 transition-all cursor-default shadow-2xs">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500/80"></span>
                        <span>{{ $skill->name }}</span>
                        @if($skill->proficiency)
                        <span class="text-[10px] font-mono text-emerald-500 font-bold opacity-80">{{ $skill->proficiency }}%</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


