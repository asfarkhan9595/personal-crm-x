<section id="projects" class="py-20 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 dark:border-cyan-500/20 text-blue-600 dark:text-cyan-400 text-xs font-semibold uppercase tracking-wider backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-blue-500 dark:bg-cyan-400 animate-pulse"></span>
                    <span>Portfolio Showcase</span>
                </div>
                <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-3">
                    Featured Projects
                </h2>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 max-w-xl">
                    Explore real-world platforms, custom AI integrations, REST APIs, and browser automation systems.
                </p>
            </div>

            <!-- Category Filter Buttons -->
            <div class="flex flex-wrap gap-2">
                <button type="button" data-filter="all" class="project-filter-btn px-4 py-2 rounded-2xl text-xs font-bold uppercase tracking-wider bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-500/25 transition-all cursor-pointer">
                    All
                </button>
                @php
                    $categories = $projects->pluck('category.name')->filter()->unique();
                @endphp
                @foreach($categories as $catName)
                <button type="button" data-filter="{{ \Illuminate\Support\Str::slug($catName) }}" class="project-filter-btn px-4 py-2 rounded-2xl text-xs font-bold uppercase tracking-wider bg-white/60 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 border border-white/80 dark:border-white/12 backdrop-blur-md hover:bg-white dark:hover:bg-slate-800 transition-all cursor-pointer">
                    {{ $catName }}
                </button>
                @endforeach
            </div>
        </div>

        <!-- Projects Card Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($projects as $proj)
                @include('themes.theme1.components.project-card', ['project' => $proj])
            @endforeach

            <!-- Explore Full Projects Catalog Glass Card (Unique Cyber Portal Design) -->
            @php
                $totalCount = $totalProjectsCount ?? count($projects);
            @endphp
            <a href="{{ route('projects.index') }}" 
               class="group relative flex flex-col overflow-hidden rounded-2xl nexus-glass-card border border-blue-500/30 dark:border-cyan-500/30 transition-all duration-500 hover:-translate-y-1.5 hover:border-blue-500/80 dark:hover:border-cyan-400/80 shadow-xl hover:shadow-[0_0_30px_rgba(6,182,212,0.25)] backdrop-blur-xl cursor-pointer">
                
                <!-- Top Cyber Terminal Showcase Frame (Matches Project Image Aspect Ratio) -->
                <div class="relative aspect-video w-full overflow-hidden bg-gradient-to-br from-blue-950 via-slate-900 to-indigo-950 p-4 flex flex-col justify-between border-b border-white/10 group-hover:from-blue-900 group-hover:to-slate-900 transition-colors duration-500">
                    
                    <!-- Ambient Radial Glow Effects -->
                    <div class="pointer-events-none absolute -right-6 -top-6 h-36 w-36 rounded-full bg-cyan-500/20 blur-2xl group-hover:bg-cyan-400/35 transition-all duration-500"></div>
                    <div class="pointer-events-none absolute -left-6 -bottom-6 h-36 w-36 rounded-full bg-blue-600/20 blur-2xl group-hover:bg-indigo-500/35 transition-all duration-500"></div>

                    <!-- Terminal Header -->
                    <div class="relative z-10 flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-cyan-400 animate-pulse"></span>
                            <span class="font-mono text-[10px] font-bold uppercase tracking-wider text-cyan-400">// CATALOG ARCHIVES</span>
                        </div>
                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-500/20 border border-cyan-400/30 px-2.5 py-0.5 text-[10px] font-mono font-bold text-cyan-300 backdrop-blur-md">
                            <span>{{ $totalCount }} Systems</span>
                        </span>
                    </div>

                    <!-- Central Cyber Visual (Interactive Portal Icon) -->
                    <div class="relative z-10 my-auto flex items-center justify-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-tr from-blue-600 to-cyan-400 text-white shadow-lg shadow-cyan-500/30 border border-white/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                            <svg class="w-6 h-6 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Terminal Code Snippet Footer -->
                    <div class="relative z-10 flex items-center justify-between text-[10px] font-mono text-slate-400 border-t border-white/10 pt-2">
                        <span class="truncate text-slate-300">&gt; load_full_archive()</span>
                        <span class="text-cyan-400 font-bold group-hover:underline">Explore All &rarr;</span>
                    </div>
                </div>

                <!-- Glass Card Body -->
                <div class="flex flex-1 flex-col p-4 justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-cyan-400 transition-colors mb-1.5 flex items-center gap-1.5">
                            <span>View Full Projects Catalog</span>
                            <svg class="w-4 h-4 text-blue-600 dark:text-cyan-400 group-hover:translate-x-1 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </h3>

                        <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300 mb-3 line-clamp-2">
                            Browse all web apps, AI microservices, Python automation workflows, REST APIs, and browser extensions.
                        </p>

                        <!-- Feature Badges Chips -->
                        <div class="flex flex-wrap gap-1 mb-3">
                            <span class="rounded-lg border border-blue-500/30 bg-blue-500/10 text-blue-600 dark:text-cyan-400 px-2 py-0.5 text-[10px] font-mono font-semibold">AI Integrations</span>
                            <span class="rounded-lg border border-indigo-500/30 bg-indigo-500/10 text-indigo-600 dark:text-indigo-300 px-2 py-0.5 text-[10px] font-mono font-semibold">Python APIs</span>
                            <span class="rounded-lg border border-cyan-500/30 bg-cyan-500/10 text-cyan-600 dark:text-cyan-300 px-2 py-0.5 text-[10px] font-mono font-semibold">Automation</span>
                        </div>
                    </div>

                    <!-- Card Footer CTA Button Strip -->
                    <div class="mt-auto border-t border-white/40 dark:border-white/10 pt-3 flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 font-extrabold text-xs text-blue-600 dark:text-cyan-400 group-hover:underline">
                            <span>Open Engineering Archives</span>
                        </span>
                        <span class="text-[11px] font-mono text-slate-500 dark:text-slate-400">
                            {{ $totalCount }} Projects &rarr;
                        </span>
                    </div>
                </div>
            </a>
        </div>



    </div>
</section>

