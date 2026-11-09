<section id="skills" class="py-12 sm:py-16 relative">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-8">
            <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 dark:border-cyan-500/20 text-blue-600 dark:text-cyan-400 text-[11px] font-semibold uppercase tracking-wider backdrop-blur-md">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 dark:bg-cyan-400 animate-pulse"></span>
                <span>Capabilities</span>
            </div>
            <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-2">
                Skills &amp; Technologies
            </h2>
        </div>

        <!-- Glass Skills Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($skillCategories as $cat)
            @php
                $slug = \Illuminate\Support\Str::slug($cat->name);
                $isAi = str_contains($slug, 'ai');
                $isBackend = str_contains($slug, 'backend');
                $isProg = str_contains($slug, 'programming') || str_contains($slug, 'language');
                $isTools = str_contains($slug, 'tool') || str_contains($slug, 'devops');
            @endphp
            <div class="p-4 sm:p-5 rounded-2xl nexus-glass-card group transition-all duration-300">
                <div class="flex items-center justify-between pb-3 mb-3 border-b border-white/40 dark:border-white/10">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 dark:border-cyan-500/20 text-blue-600 dark:text-cyan-400 group-hover:bg-blue-600 group-hover:text-white transition-all">
                            @if($isAi)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            @elseif($isBackend)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"></path></svg>
                            @elseif($isProg)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                            @elseif($isTools)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a2 2 0 01-2 2 2 2 0 01-2-2V4zm-6 8a2 2 0 114 0v1a2 2 0 01-2 2 2 2 0 01-2-2v-1z"></path></svg>
                            @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            @endif
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-cyan-400 transition-colors">
                            {{ $cat->name }}
                        </h3>
                    </div>
                    <span class="text-[10px] font-mono text-slate-400">
                        {{ $cat->skills->count() }}
                    </span>
                </div>

                <div class="flex flex-wrap gap-1.5">
                    @foreach($cat->skills as $skill)
                        @include('themes.theme1.components.skill-card', ['skill' => $skill])
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>
