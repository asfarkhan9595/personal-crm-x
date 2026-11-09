<section id="skills" class="py-20 sm:py-24 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 dark:border-cyan-500/20 text-blue-600 dark:text-cyan-400 text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-blue-500 dark:bg-cyan-400 animate-pulse"></span>
                <span>Tech Stack &amp; Skills</span>
            </span>
            <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-3">
                Technologies &amp; Expertise
            </h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300 max-w-xl mx-auto">
                Tools, frameworks, and programming languages utilized to build production applications.
            </p>
        </div>

        @if(isset($skills) && $skills->count() > 0)
        <!-- Skills Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($skills as $skill)
            <div class="group p-5 rounded-3xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border border-white/80 dark:border-white/10 shadow-lg hover:shadow-xl hover:shadow-blue-500/10 hover:border-blue-500/40 hover:-translate-y-1 transition-all duration-300 flex flex-col items-center justify-center text-center space-y-3">
                @if($skill->icon_url || $skill->icon)
                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 flex items-center justify-center text-blue-600 dark:text-cyan-400 group-hover:scale-110 transition-transform">
                    @if(str_starts_with($skill->icon_url ?? $skill->icon ?? '', 'http') || str_contains($skill->icon_url ?? $skill->icon ?? '', '/'))
                    <img src="{{ $skill->icon_url ?? asset('storage/' . ltrim($skill->icon, '/')) }}" alt="{{ $skill->name }}" class="w-6 h-6 object-contain">
                    <img src="{{ format_image_url($skill->icon) }}" alt="{{ $skill->name }}" class="w-6 h-6 object-contain">
                    @else
                    <span class="font-bold text-sm">{{ strtoupper(substr($skill->name, 0, 2)) }}</span>
                    @endif
                </div>
                @else
                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 flex items-center justify-center text-blue-600 dark:text-cyan-400 font-bold text-sm group-hover:scale-110 transition-transform">
                    {{ strtoupper(substr($skill->name, 0, 2)) }}
                </div>
                @endif

                <div>
                    <h3 class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-cyan-400 transition-colors">
                        {{ $skill->name }}
                    </h3>
                    @if(isset($skill->proficiency) && $skill->proficiency)
                    <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono mt-0.5 block">
                        {{ $skill->proficiency }}%
                    </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- Fallback Default Stack Badges if DB empty -->
        <div class="flex flex-wrap justify-center gap-3 max-w-4xl mx-auto">
            @foreach(['Laravel', 'PHP', 'Python', 'FastAPI', 'MySQL', 'JavaScript', 'Tailwind CSS', 'Docker', 'REST APIs', 'Git', 'Redis', 'Alpine.js'] as $tech)
            <div class="px-5 py-3 rounded-full bg-white/70 dark:bg-slate-900/70 border border-white/80 dark:border-white/10 backdrop-blur-xl text-xs font-bold text-slate-800 dark:text-slate-200 shadow-md hover:border-blue-500/40 hover:text-blue-600 dark:hover:text-cyan-400 transition-all cursor-default">
                {{ $tech }}
            </div>
            @endforeach
        </div>
        @endif

    </div>
</section>
