<section id="experience" class="py-20 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 dark:border-cyan-500/20 text-blue-600 dark:text-cyan-400 text-xs font-semibold uppercase tracking-wider backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-blue-500 dark:bg-cyan-400 animate-pulse"></span>
                <span>Work History</span>
            </div>
            <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-3">
                Experience Timeline
            </h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 max-w-xl mx-auto">
                Professional software engineering roles, company achievements, and backend responsibility.
            </p>
        </div>

        <!-- Glass Timeline Grid -->
        <div class="max-w-4xl mx-auto space-y-6">
            @foreach($experience as $exp)
                @include('themes.theme1.components.experience-card', ['experience' => $exp])
            @endforeach
        </div>

    </div>
</section>

