<section id="projects" class="py-20 relative bg-slate-100/50 dark:bg-slate-900/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-xs font-bold uppercase tracking-widest">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Featured Work
                </div>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 mt-3 sm:text-4xl tracking-tight">
                    Selected Projects
                </h2>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 max-w-xl">
                    Explore real-world full-stack platforms, AI integrations, tools, and backend systems.
                </p>
            </div>

            <!-- Project Category Filter Buttons -->
            <div class="flex flex-wrap gap-2 mt-6 md:mt-0">
                <button type="button" data-filter="all" class="project-filter-btn px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider bg-emerald-500 text-white shadow-md transition-all cursor-pointer">
                    All
                </button>
                @php
                    $categories = $projects->pluck('category.name')->filter()->unique();
                @endphp
                @foreach($categories as $catName)
                <button type="button" data-filter="{{ \Illuminate\Support\Str::slug($catName) }}" class="project-filter-btn px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider bg-slate-200/80 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-300 dark:hover:bg-slate-700 transition-all cursor-pointer">
                    {{ $catName }}
                </button>
                @endforeach
            </div>
        </div>

        <!-- Projects Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($projects as $proj)
            @php
                $img = $proj->cover_image ?? ($proj->featured_image ?? ($proj->images->first()?->image_path));
                $imgUrl = format_image_url($img);
                $videoUrl = format_image_url($proj->video_url);
            @endphp
            <div class="project-card-item group relative flex flex-col overflow-hidden rounded-2xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-slate-900/80 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-emerald-500/50 hover:shadow-xl hover:shadow-emerald-500/10 cursor-pointer"
                 data-category="{{ \Illuminate\Support\Str::slug($proj->category->name ?? 'other') }}"
                 data-category-name="{{ $proj->category->name ?? 'Project' }}"
                 data-title="{{ $proj->title }}"
                 data-short-description="{{ $proj->short_description ?? ($proj->summary ?? $proj->description) }}"
                 data-description="{{ $proj->description }}"
                 data-problem="{{ $proj->problem }}"
                 data-solution="{{ $proj->solution }}"
                 data-architecture="{{ $proj->architecture }}"
                 data-challenges="{{ $proj->challenges }}"
                 data-learned="{{ $proj->what_i_learned }}"
                 data-image="{{ $imgUrl }}"
                 data-video-url="{{ $videoUrl }}"
                 data-demo-url="{{ $proj->live_demo_url ?? $proj->demo_url }}"
                 data-github-url="{{ $proj->github_url }}"
                 data-project-slug="{{ $proj->slug }}"
                 data-features='@json($proj->features->pluck("feature"))'
                 data-technologies='@json($proj->technologies->pluck("name"))'
                 data-gallery='@json($proj->images->map(fn($i) => format_image_url($i->image_path)))'>

                <!-- Thumbnail Container -->
                <div class="relative flex aspect-video w-full items-center justify-center overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-950">
                    @if($imgUrl)
                    <img src="{{ $imgUrl }}" alt="{{ $proj->title }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>
                    @else
                    <!-- Creative Styled Code Mesh Fallback (No ugly missing screenshot frame) -->
                    <div class="relative inset-0 w-full h-full p-6 flex flex-col justify-between bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-emerald-900/30 via-slate-900 to-slate-950 overflow-hidden">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-emerald-500/10 rounded-full blur-2xl"></div>
                        <div class="flex items-center justify-between z-10">
                            <span class="font-mono text-xs text-emerald-400/80 tracking-wider">PROJECT // {{ strtoupper($proj->category->name ?? 'SYSTEM') }}</span>
                            <div class="flex gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-red-500/60"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-yellow-500/60"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-green-500/60"></span>
                            </div>
                        </div>
                        <div class="z-10 py-4 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                            </div>
                            <h4 class="text-base font-bold text-slate-200 line-clamp-1 px-4">{{ $proj->title }}</h4>
                        </div>
                        <div class="flex items-center justify-between text-[11px] font-mono text-slate-400 z-10 border-t border-slate-800/80 pt-2">
                            <span>Status: Production</span>
                            <span class="text-emerald-400">View Architecture →</span>
                        </div>
                    </div>
                    @endif

                    <!-- Top Overlay Badges -->
                    <div class="absolute top-3 left-3 right-3 flex items-center justify-between pointer-events-none z-10">
                        <div class="flex gap-1.5 flex-wrap">
                            @if(!empty($proj->featured) || !empty($proj->is_featured))
                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-500/15 dark:bg-amber-400/15 border border-amber-500/30 text-amber-600 dark:text-amber-300 px-2.5 py-0.5 text-xs font-bold backdrop-blur-xl shadow-sm">
                                <svg class="w-3 h-3 text-amber-500 dark:text-amber-400 fill-amber-500 dark:fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                ★ Featured
                            </span>
                            @endif
                            @if($proj->category)
                            <span class="rounded-full bg-slate-950/70 border border-slate-700/60 text-slate-200 px-2.5 py-0.5 text-xs font-medium backdrop-blur-md">
                                {{ $proj->category->name }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Quick View Floating Button on Hover -->
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 bg-slate-950/30 backdrop-blur-md z-10">
                        <button type="button" class="open-project-modal inline-flex items-center gap-2 rounded-xl bg-blue-600/80 hover:bg-blue-600 border border-blue-400/30 text-white font-bold px-4 py-2 text-xs shadow-lg backdrop-blur-xl transition-all hover:scale-105 cursor-pointer" data-project-slug="{{ $proj->slug }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <span>Interactive Showcase</span>
                        </button>
                    </div>
                </div>

                <!-- Content Body -->
                <div class="flex flex-1 flex-col p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white group-hover:text-emerald-500 transition-colors line-clamp-1">
                            {{ $proj->title }}
                        </h3>
                    </div>

                    <p class="mb-5 line-clamp-2 text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                        {{ $proj->short_description ?? ($proj->summary ?? $proj->description) }}
                    </p>

                    <!-- Tech Stack Tags -->
                    @if($proj->technologies && $proj->technologies->count() > 0)
                    <div class="mb-6 flex flex-wrap gap-1.5">
                        @foreach($proj->technologies->take(5) as $tech)
                        <span class="rounded-lg border border-slate-200/80 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/60 px-2.5 py-1 text-xs font-mono font-medium text-slate-700 dark:text-slate-300">
                            {{ $tech->name }}
                        </span>
                        @endforeach
                        @if($proj->technologies->count() > 5)
                        <span class="rounded-lg bg-slate-100 dark:bg-slate-800 px-2 py-1 text-xs font-mono text-slate-500">
                            +{{ $proj->technologies->count() - 5 }}
                        </span>
                        @endif
                    </div>
                    @endif

                    <!-- Card Footer Actions -->
                    <div class="mt-auto flex items-center justify-between border-t border-slate-100 dark:border-slate-800/80 pt-4">
                        <button type="button" data-project-slug="{{ $proj->slug }}" class="open-project-modal inline-flex items-center gap-1.5 text-xs font-bold text-emerald-500 hover:text-emerald-600 transition-colors cursor-pointer">
                            <span>Details</span>
                            <svg class="h-3.5 w-3.5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>

                        <div class="flex items-center gap-3">
                            @if($proj->live_demo_url || $proj->demo_url)
                            <a href="{{ $proj->live_demo_url ?? $proj->demo_url }}" target="_blank" rel="noopener noreferrer" onclick="event.stopPropagation()" class="inline-flex items-center gap-1 text-xs font-medium text-slate-600 hover:text-emerald-500 dark:text-slate-400 dark:hover:text-emerald-400 transition-colors" title="Live Demo">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                <span>Demo</span>
                            </a>
                            @else
                            <span class="inline-flex items-center gap-1 text-xs text-slate-400 dark:text-slate-600 cursor-not-allowed" title="Demo Unavailable">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                <span>Demo N/A</span>
                            </span>
                            @endif

                            @if($proj->github_url)
                            <a href="{{ $proj->github_url }}" target="_blank" rel="noopener noreferrer" onclick="event.stopPropagation()" class="inline-flex items-center gap-1 text-xs font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white transition-colors" title="View Source Code">
                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                                <span>Code</span>
                            </a>
                            @else
                            <span class="inline-flex items-center gap-1 text-xs text-slate-400 dark:text-slate-600 cursor-not-allowed" title="Code Unavailable">
                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                                <span>Code N/A</span>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

