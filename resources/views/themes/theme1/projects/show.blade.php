@extends('layouts.theme1')

@section('title', $project->title . ' — Theme 1 Case Study')
@section('meta_description', $project->short_description ?? ($project->summary ?? $project->description))

@section('content')
<article class="py-20 sm:py-24 relative min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Back Navigation -->
        <div class="mb-8">
            <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-cyan-400 hover:underline transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Back to All Projects</span>
            </a>
        </div>

        <!-- Case Study Header Card -->
        <header class="p-6 sm:p-8 rounded-3xl nexus-glass-card space-y-4 mb-8">
            <div class="flex flex-wrap items-center gap-2">
                @if($project->featured)
                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 dark:border-cyan-500/20 px-3 py-1 text-xs font-bold text-blue-600 dark:text-cyan-400 backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-blue-500 dark:bg-cyan-400 animate-pulse"></span>
                    Featured Project
                </span>
                @endif

                @if($project->category)
                <span class="rounded-full bg-white/60 dark:bg-slate-800/60 border border-white/80 dark:border-white/12 px-3 py-1 text-xs font-semibold text-slate-700 dark:text-slate-300 backdrop-blur-md">
                    {{ $project->category->name }}
                </span>
                @endif
            </div>

            <h1 class="text-2xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                {{ $project->title }}
            </h1>

            <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 font-normal leading-relaxed max-w-3xl">
                {{ $project->short_description ?? ($project->summary ?? $project->description) }}
            </p>

            <!-- Action Buttons Header -->
            <div class="pt-2 flex flex-wrap gap-3">
                @if($project->live_demo_url || $project->demo_url)
                <a href="{{ $project->live_demo_url ?? $project->demo_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xs shadow-lg shadow-blue-500/25 border border-white/20 transition-all hover:scale-105">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    <span>Launch Live Demo</span>
                </a>
                @endif

                @if($project->github_url)
                <a href="{{ $project->github_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white/70 dark:bg-slate-800/70 hover:bg-white dark:hover:bg-slate-800 text-slate-800 dark:text-white font-bold text-xs transition-all border border-white/80 dark:border-white/12 backdrop-blur-xl shadow-sm hover:scale-105">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                    <span>View GitHub Source</span>
                </a>
                @endif
            </div>
        </header>

        <!-- Video / Gallery Showcase Screen -->
        @php
            $pageVideoUrl = $project->video_url ? (str_starts_with($project->video_url, 'http') ? $project->video_url : asset('storage/' . ltrim($project->video_url, '/'))) : '';
            $pageVideoUrl = $project->video_url ? format_image_url($project->video_url) : '';
            $galleryImgs = collect();
            if ($project->cover_image) $galleryImgs->push($project->cover_image);
            if ($project->featured_image && $project->featured_image !== $project->cover_image) $galleryImgs->push($project->featured_image);
            foreach ($project->images as $gi) {
                if ($gi->image_path) $galleryImgs->push($gi->image_path);
            }
            $galleryImgs = $galleryImgs->unique()->values();
            $galleryUrls = $galleryImgs->map(fn($p) => str_starts_with($p, 'http') ? $p : asset('storage/' . ltrim($p, '/')))->all();
            $galleryUrls = $galleryImgs->map(fn($p) => format_image_url($p))->all();
            $mainImgUrl = $galleryUrls[0] ?? '';
        @endphp

        @if($pageVideoUrl)
        <div class="mb-10 p-2.5 rounded-3xl nexus-glass-card">
            <div class="relative aspect-video w-full overflow-hidden rounded-2xl bg-slate-950 flex items-center justify-center">
                <video controls class="w-full h-full object-contain rounded-2xl" preload="metadata">
                    <source src="{{ $pageVideoUrl }}" type="video/mp4">
                    Your browser does not support HTML5 video.
                </video>
            </div>
        </div>
        @endif

        @if(count($galleryUrls) > 0)
        <div id="page-gallery-wrapper" class="mb-10 p-2.5 rounded-3xl nexus-glass-card space-y-2.5">
            <div class="relative aspect-video w-full overflow-hidden rounded-2xl bg-slate-950 flex items-center justify-center group">
                <img id="page-main-img" src="{{ $mainImgUrl }}" alt="{{ $project->title }}" class="w-full h-full object-contain transition-opacity duration-300">
                
                @if(count($galleryUrls) > 1)
                <button type="button" id="page-gallery-prev" class="absolute left-3 top-1/2 -translate-y-1/2 flex h-10 w-10 items-center justify-center rounded-full bg-white/80 dark:bg-slate-950/80 text-slate-800 dark:text-white backdrop-blur-md border border-white/40 dark:border-white/20 hover:bg-blue-600 hover:text-white transition-all shadow-xl cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button type="button" id="page-gallery-next" class="absolute right-3 top-1/2 -translate-y-1/2 flex h-10 w-10 items-center justify-center rounded-full bg-white/80 dark:bg-slate-950/80 text-slate-800 dark:text-white backdrop-blur-md border border-white/40 dark:border-white/20 hover:bg-blue-600 hover:text-white transition-all shadow-xl cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </button>
                @endif
            </div>

            @if(count($galleryUrls) > 1)
            <div id="page-gallery-thumbs" class="grid grid-cols-4 sm:grid-cols-6 gap-2 p-1">
                @foreach($galleryUrls as $index => $gUrl)
                <button type="button" data-index="{{ $index }}" data-src="{{ $gUrl }}" class="page-thumb-btn aspect-video overflow-hidden rounded-xl border-2 {{ $index === 0 ? 'border-blue-600 dark:border-cyan-400 opacity-100' : 'border-transparent opacity-60' }} hover:opacity-100 transition-all cursor-pointer bg-slate-950">
                    <img src="{{ $gUrl }}" alt="Thumbnail {{ $index + 1 }}" class="w-full h-full object-cover">
                </button>
                @endforeach
            </div>
            @endif
        </div>
        @endif

        <!-- Complete Case Study Details Grid -->
        <div class="space-y-6">
            
            <!-- Unified Problem & Solution Glass Card (Single Card, Divided) -->
            @if($project->problem || $project->solution)
            <div class="p-6 sm:p-7 rounded-3xl nexus-glass-card space-y-5">
                @if($project->problem)
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-amber-600 dark:text-amber-400 font-bold text-xs font-mono uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span>// PROBLEM STATEMENT</span>
                    </div>
                    <p class="text-xs sm:text-sm leading-relaxed text-slate-800 dark:text-slate-200 font-normal">{{ $project->problem }}</p>
                </div>
                @endif

                @if($project->problem && $project->solution)
                <div class="border-t border-slate-200/80 dark:border-white/10"></div>
                @endif

                @if($project->solution)
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-bold text-xs font-mono uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                        <span>// ARCHITECTURAL SOLUTION</span>
                    </div>
                    <p class="text-xs sm:text-sm leading-relaxed text-slate-800 dark:text-slate-200 font-normal">{{ $project->solution }}</p>
                </div>
                @endif
            </div>
            @endif

            <!-- Main Narrative Overview -->
            @if($project->description)
            <div class="p-6 sm:p-7 rounded-3xl nexus-glass-card">
                <h2 class="text-xs font-mono font-bold uppercase tracking-wider text-blue-600 dark:text-cyan-400 mb-2.5">// PROJECT OVERVIEW & DETAILS</h2>
                <div class="text-xs sm:text-sm leading-relaxed text-slate-800 dark:text-slate-200 space-y-3">
                    {!! nl2br(e($project->description)) !!}
                </div>
            </div>
            @endif

            <!-- Data Flow / Architecture -->
            @if($project->architecture)
            @php
                $archSteps = array_filter(array_map('trim', preg_split('/->|→/', $project->architecture)));
            @endphp
            @if(count($archSteps) > 0)
            <div class="p-6 sm:p-7 rounded-3xl nexus-glass-card">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-blue-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <h3 class="text-xs font-mono font-bold uppercase tracking-wider text-blue-600 dark:text-cyan-400">// SYSTEM DATA FLOW & ARCHITECTURE</h3>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    @foreach($archSteps as $idx => $step)
                    <span class="px-3 py-1.5 rounded-lg bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 dark:border-cyan-500/20 text-blue-700 dark:text-cyan-400 font-mono text-xs font-semibold backdrop-blur-md">
                        {{ $step }}
                    </span>
                    @if($idx < count($archSteps) - 1)
                    <span class="text-slate-400 dark:text-slate-500 font-bold text-xs">→</span>
                    @endif
                    @endforeach
                </div>
            </div>
            @endif
            @endif

            <!-- Key Features & Capabilities (Slim Field Style) -->
            @if($project->features && $project->features->count() > 0)
            <div class="p-6 sm:p-7 rounded-3xl nexus-glass-card">
                <h3 class="text-xs font-mono font-bold uppercase tracking-wider text-blue-600 dark:text-cyan-400 mb-3">// KEY FEATURES & CAPABILITIES</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    @foreach($project->features as $feat)
                    <div class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl bg-white/60 dark:bg-slate-900/60 border border-white/80 dark:border-white/12 backdrop-blur-md shadow-sm transition-all hover:bg-white dark:hover:bg-slate-800">
                        <svg class="w-3.5 h-3.5 text-blue-600 dark:text-cyan-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-xs font-medium text-slate-800 dark:text-slate-200 truncate">{{ $feat->feature }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Technologies & Stack Used (Slim Pills) -->
            @if($project->technologies && $project->technologies->count() > 0)
            <div class="p-6 sm:p-7 rounded-3xl nexus-glass-card">
                <h3 class="text-xs font-mono font-bold uppercase tracking-wider text-blue-600 dark:text-cyan-400 mb-3">// TECHNOLOGIES & STACK</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($project->technologies as $tech)
                    <span class="rounded-lg border border-white/80 dark:border-white/12 bg-white/60 dark:bg-slate-800/60 px-3 py-1.5 text-xs font-mono font-semibold text-slate-800 dark:text-slate-200 backdrop-blur-md shadow-sm">
                        {{ $tech->name }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Technical Challenges & Lessons Learned (Unified Divided Glass Card) -->
            @if($project->challenges || $project->what_i_learned)
            <div class="p-6 sm:p-7 rounded-3xl nexus-glass-card space-y-5">
                @if($project->challenges)
                <div class="space-y-2">
                    <h4 class="text-xs font-mono font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">// TECHNICAL CHALLENGES</h4>
                    <p class="text-xs sm:text-sm leading-relaxed text-slate-800 dark:text-slate-200 font-normal">{{ $project->challenges }}</p>
                </div>
                @endif

                @if($project->challenges && $project->what_i_learned)
                <div class="border-t border-slate-200/80 dark:border-white/10"></div>
                @endif

                @if($project->what_i_learned)
                <div class="space-y-2">
                    <h4 class="text-xs font-mono font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400">// KEY TAKEAWAYS & IMPACT</h4>
                    <p class="text-xs sm:text-sm leading-relaxed text-slate-800 dark:text-slate-200 font-normal">{{ $project->what_i_learned }}</p>
                </div>
                @endif
            </div>
            @endif

            <!-- Footer Action Links -->
            <div class="pt-6 border-t border-slate-200 dark:border-white/10 flex flex-wrap items-center justify-between gap-4">
                <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-400 hover:text-blue-600 dark:hover:text-cyan-400 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Return to All Projects</span>
                </a>

                <div class="flex flex-wrap gap-3">
                    @if($project->live_demo_url || $project->demo_url)
                    <a href="{{ $project->live_demo_url ?? $project->demo_url }}" target="_blank" rel="noopener noreferrer" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xs shadow-md transition-all">
                        Live Demo
                    </a>
                    @endif
                    @if($project->github_url)
                    <a href="{{ $project->github_url }}" target="_blank" rel="noopener noreferrer" class="px-5 py-2.5 rounded-xl bg-white/70 dark:bg-slate-800/70 hover:bg-white dark:hover:bg-slate-800 text-slate-800 dark:text-white font-bold text-xs transition-all border border-white/80 dark:border-white/12 backdrop-blur-xl">
                        GitHub Source
                    </a>
                    @endif
                </div>
            </div>

        </div>
    </div>
</article>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const mainImg = document.getElementById('page-main-img');
    const thumbs = document.querySelectorAll('.page-thumb-btn');
    const prevBtn = document.getElementById('page-gallery-prev');
    const nextBtn = document.getElementById('page-gallery-next');

    if (!thumbs || thumbs.length === 0) return;

    let currentIdx = 0;
    const updateGallery = (index) => {
        currentIdx = index;
        const targetBtn = thumbs[currentIdx];
        if (targetBtn && mainImg) {
            mainImg.src = targetBtn.getAttribute('data-src');
            thumbs.forEach((btn, i) => {
                if (i === currentIdx) {
                    btn.classList.add('border-blue-600', 'dark:border-cyan-400', 'opacity-100');
                    btn.classList.remove('border-transparent', 'opacity-60');
                } else {
                    btn.classList.remove('border-blue-600', 'dark:border-cyan-400', 'opacity-100');
                    btn.classList.add('border-transparent', 'opacity-60');
                }
            });
        }
    };

    thumbs.forEach((btn, idx) => {
        btn.addEventListener('click', () => updateGallery(idx));
    });

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            let newIdx = (currentIdx - 1 + thumbs.length) % thumbs.length;
            updateGallery(newIdx);
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            let newIdx = (currentIdx + 1) % thumbs.length;
            updateGallery(newIdx);
        });
    }
});
</script>
@endpush
