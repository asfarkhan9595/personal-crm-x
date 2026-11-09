<div id="project-modal-overlay" class="hidden fixed inset-0 z-50 bg-slate-950/60 dark:bg-black/80 backdrop-blur-xl flex items-center justify-center p-4 sm:p-6 overflow-y-auto">
    <div class="relative z-10 w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded-3xl border border-slate-200/80 dark:border-white/10 bg-white/95 dark:bg-slate-950/95 text-slate-900 dark:text-slate-100 shadow-2xl shadow-indigo-500/5 my-auto flex flex-col transition-all duration-300">

        {{-- Terminal Window Header Bar --}}
        <div class="px-6 py-4 border-b border-slate-200/80 dark:border-white/10 flex items-center justify-between bg-slate-50/90 dark:bg-slate-900/90 backdrop-blur-md sticky top-0 z-30">
            <div class="flex items-center gap-2 font-mono text-xs">
                <span class="w-3 h-3 rounded-full bg-red-400"></span>
                <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                <span class="ml-2 font-semibold text-slate-500 dark:text-slate-400">// QUICK_PREVIEW_SPEC</span>
            </div>

            {{-- Close Button --}}
            <button type="button" class="close-project-modal flex h-8 w-8 items-center justify-center rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all cursor-pointer" aria-label="Close modal">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-6 sm:p-8 space-y-6">

            {{-- Header Title & Badges --}}
            <div>
                <div class="mb-3 flex flex-wrap items-center gap-2 font-mono">
                    <span id="modal-featured-badge" class="hidden rounded-full bg-amber-500/15 border border-amber-500/30 px-3 py-1 text-xs font-bold text-amber-600 dark:text-amber-400">★ FEATURED</span>
                    <span id="modal-category-badge" class="rounded-full bg-indigo-500/10 dark:bg-cyan-500/10 border border-indigo-500/20 dark:border-cyan-500/20 px-3 py-1 text-xs font-semibold text-indigo-600 dark:text-cyan-400"></span>
                </div>
                <h2 id="modal-title" class="text-2xl font-extrabold tracking-tight sm:text-3xl text-slate-900 dark:text-white font-mono"></h2>
            </div>

            {{-- Unified Media Showcase (Video & Images in single frame slider) --}}
            <div id="modal-media-wrapper" class="hidden overflow-hidden rounded-2xl bg-slate-950 p-2 border border-slate-200/80 dark:border-white/10 shadow-lg">
                <div class="relative flex aspect-video w-full items-center justify-center overflow-hidden rounded-xl bg-slate-950 group">
                    
                    {{-- Video Container --}}
                    <div id="modal-video-container" class="hidden w-full h-full flex items-center justify-center">
                        <video id="modal-video-player" controls class="w-full h-full object-contain rounded-xl" preload="metadata">
                            <source id="modal-video-source" src="" type="video/mp4">
                            Your browser does not support HTML5 video.
                        </video>
                    </div>

                    {{-- Main Image Container --}}
                    <img id="modal-main-img" src="" alt="Project Showcase" class="hidden h-full w-full object-contain">

                    {{-- Left Arrow --}}
                    <button type="button" id="modal-prev-btn"
                        class="hidden absolute left-3 top-1/2 -translate-y-1/2 flex h-9 w-9 items-center justify-center rounded-full bg-slate-950/80 text-white backdrop-blur-md border border-white/20 hover:bg-indigo-600 dark:hover:bg-cyan-500 transition-all shadow-lg cursor-pointer z-20"
                        aria-label="Previous media">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>

                    {{-- Right Arrow --}}
                    <button type="button" id="modal-next-btn"
                        class="hidden absolute right-3 top-1/2 -translate-y-1/2 flex h-9 w-9 items-center justify-center rounded-full bg-slate-950/80 text-white backdrop-blur-md border border-white/20 hover:bg-indigo-600 dark:hover:bg-cyan-500 transition-all shadow-lg cursor-pointer z-20"
                        aria-label="Next media">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>

                {{-- Thumbnail Strip (Video + Images) --}}
                <div id="modal-gallery-thumbs" class="hidden grid grid-cols-4 sm:grid-cols-6 gap-2 mt-2"></div>
            </div>

            {{-- Sections --}}
            <div class="space-y-6">

                {{-- Overview --}}
                <div id="modal-overview-wrapper" class="hidden">
                    <h3 class="mb-2 text-xs font-mono font-bold uppercase tracking-wider text-indigo-600 dark:text-cyan-400">// OVERVIEW</h3>
                    <div class="p-4 rounded-2xl bg-slate-50/80 dark:bg-slate-900/80 border border-slate-200/80 dark:border-white/10">
                        <p id="modal-short-desc" class="text-slate-700 dark:text-slate-300 text-xs sm:text-sm leading-relaxed"></p>
                    </div>
                </div>

                {{-- Problem & Solution Grid --}}
                <div id="modal-problem-solution-wrapper" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div id="modal-problem-wrapper" class="hidden rounded-2xl border border-amber-500/30 bg-amber-500/10 dark:bg-amber-950/20 p-4 shadow-sm space-y-1">
                        <h3 class="text-xs font-mono font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">// PROBLEM</h3>
                        <p id="modal-problem" class="text-slate-700 dark:text-slate-300 text-xs sm:text-sm leading-relaxed"></p>
                    </div>

                    <div id="modal-solution-wrapper" class="hidden rounded-2xl border border-emerald-500/30 bg-emerald-500/10 dark:bg-emerald-950/20 p-4 shadow-sm space-y-1">
                        <h3 class="text-xs font-mono font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">// SOLUTION</h3>
                        <p id="modal-solution" class="text-slate-700 dark:text-slate-300 text-xs sm:text-sm leading-relaxed"></p>
                    </div>
                </div>

                {{-- Architecture Flow --}}
                <div id="modal-arch-wrapper" class="hidden">
                    <h3 class="mb-2 text-xs font-mono font-bold uppercase tracking-wider text-indigo-600 dark:text-cyan-400">// ARCHITECTURE PIPELINE</h3>
                    <div id="modal-arch-container" class="flex flex-wrap items-center gap-2 p-4 rounded-2xl bg-slate-50/80 dark:bg-slate-900/80 border border-slate-200/80 dark:border-white/10 font-mono text-xs"></div>
                </div>

                {{-- Key Features --}}
                <div id="modal-features-wrapper" class="hidden">
                    <h3 class="mb-2 text-xs font-mono font-bold uppercase tracking-wider text-indigo-600 dark:text-cyan-400">// KEY FEATURES</h3>
                    <ul id="modal-features-list" class="space-y-2 list-none pl-0"></ul>
                </div>

                {{-- Technologies --}}
                <div id="modal-tech-wrapper" class="hidden">
                    <h3 class="mb-2 text-xs font-mono font-bold uppercase tracking-wider text-indigo-600 dark:text-cyan-400">// TECHNOLOGIES USED</h3>
                    <div id="modal-tech-list" class="flex flex-wrap gap-2"></div>
                </div>

                {{-- Challenges & Learnings --}}
                <div id="modal-challenges-wrapper" class="hidden">
                    <h3 class="mb-1.5 text-xs font-mono font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">// CHALLENGES</h3>
                    <div class="p-4 rounded-2xl bg-slate-50/80 dark:bg-slate-900/80 border border-amber-500/20">
                        <p id="modal-challenges" class="text-slate-700 dark:text-slate-300 text-xs sm:text-sm leading-relaxed"></p>
                    </div>
                </div>

                <div id="modal-learned-wrapper" class="hidden">
                    <h3 class="mb-1.5 text-xs font-mono font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">// WHAT I LEARNED</h3>
                    <div class="p-4 rounded-2xl bg-slate-50/80 dark:bg-slate-900/80 border border-emerald-500/20">
                        <p id="modal-learned" class="text-slate-700 dark:text-slate-300 text-xs sm:text-sm leading-relaxed"></p>
                    </div>
                </div>

                {{-- Action Buttons Footer Bar --}}
                <div class="flex flex-wrap items-center justify-between gap-4 border-t border-slate-200/80 dark:border-white/10 pt-5 font-mono">
                    <div class="flex flex-wrap items-center gap-3">
                        {{-- Live Demo Button (Active) --}}
                        <a id="modal-demo-btn" href="#" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 dark:bg-emerald-500 hover:bg-indigo-700 dark:hover:bg-emerald-400 text-white dark:text-slate-950 font-bold px-4 py-2.5 text-xs shadow-md transition-all hover:scale-105 cursor-pointer">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            Live Demo ↗
                        </a>
                        {{-- Live Demo Button (Disabled) --}}
                        <span id="modal-demo-disabled"
                           class="hidden inline-flex items-center gap-2 rounded-xl bg-slate-100 dark:bg-slate-900 text-slate-400 dark:text-slate-500 font-medium px-4 py-2.5 text-xs border border-slate-200 dark:border-white/10 cursor-not-allowed">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            Demo Unavailable
                        </span>

                        {{-- GitHub Code Button (Active) --}}
                        <a id="modal-github-btn" href="#" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-white/10 text-slate-800 dark:text-slate-200 font-bold px-4 py-2.5 text-xs hover:bg-slate-200 dark:hover:bg-slate-800 transition-all hover:scale-105 cursor-pointer">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                            GitHub REPO
                        </a>
                        {{-- GitHub Code Button (Disabled) --}}
                        <span id="modal-github-disabled"
                           class="hidden inline-flex items-center gap-2 rounded-xl bg-slate-100 dark:bg-slate-900 text-slate-400 dark:text-slate-500 font-medium px-4 py-2.5 text-xs border border-slate-200 dark:border-white/10 cursor-not-allowed">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                            Code N/A
                        </span>
                    </div>

                    <a id="modal-details-page-btn" href="#" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 dark:text-cyan-400 hover:underline transition-colors">
                        <span>Full Details Page ↗</span>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
