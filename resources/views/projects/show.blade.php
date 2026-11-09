@extends('layouts.app')

@section('title', $project->title . ' - Detailed Case Study')
@section('meta_description', $project->short_description ?? ($project->summary ?? $project->description))

@section('content')
<article class="py-20 sm:py-24 relative min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Navigation Back Link -->
        <div class="mb-8">
            <a href="{{ route('home') }}#projects" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-emerald-500 hover:text-emerald-600 dark:text-emerald-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Back to All Projects</span>
            </a>
        </div>

        <!-- Case Study Header -->
        <header class="space-y-4 mb-10">
            <div class="flex flex-wrap items-center gap-2">
                @if($project->featured)
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 px-3 py-1 text-xs font-bold text-emerald-500">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Featured Showcase
                </span>
                @endif

                @if($project->category)
                <span class="rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700/60 px-3 py-1 text-xs font-semibold text-slate-600 dark:text-slate-300">
                    {{ $project->category->name }}
                </span>
                @endif
            </div>

            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight leading-tight">
                {{ $project->title }}
            </h1>

            <p class="text-lg text-slate-600 dark:text-slate-300 font-medium leading-relaxed max-w-3xl">
                {{ $project->short_description ?? ($project->summary ?? $project->description) }}
            </p>

            <!-- Action Buttons Header -->
            <div class="pt-4 flex flex-wrap gap-4">
                @if($project->live_demo_url || $project->demo_url)
                <a href="{{ $project->live_demo_url ?? $project->demo_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm transition-all shadow-lg hover:shadow-emerald-500/25">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    <span>Launch Live Demo</span>
                </a>
                @endif

                @if($project->github_url)
                <a href="{{ $project->github_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white font-bold text-sm transition-all shadow border border-slate-700/50">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                    <span>View GitHub Source</span>
                </a>
                @endif
            </div>
        </header>

        <!-- Media Showcase Banner -->
        @php
            $galleryImgs = collect();
            if ($project->cover_image) $galleryImgs->push($project->cover_image);
            if ($project->featured_image && $project->featured_image !== $project->cover_image) $galleryImgs->push($project->featured_image);
            foreach ($project->images as $gi) {
                if ($gi->image_path) $galleryImgs->push($gi->image_path);
            }
            $galleryImgs = $galleryImgs->unique()->values();
            $galleryUrls = $galleryImgs->map(fn($p) => format_image_url($p))->all();
            $mainImgUrl = $galleryUrls[0] ?? '';
        @endphp

        @if(count($galleryUrls) > 0)
        <div id="page-gallery-wrapper" class="mb-12 rounded-3xl overflow-hidden shadow-xl dark:shadow-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 space-y-3">
            <!-- Main Display Image Screen with Adaptive Prev/Next Controls -->
            <div class="relative aspect-video w-full overflow-hidden rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800/80 flex items-center justify-center group">
                <img id="page-main-img" src="{{ $mainImgUrl }}" alt="{{ $project->title }}" class="w-full h-full object-contain transition-all duration-300">
                
                @if(count($galleryUrls) > 1)
                <!-- Prev Button -->
                <button type="button" id="page-gallery-prev" class="absolute left-3 top-1/2 -translate-y-1/2 flex h-11 w-11 items-center justify-center rounded-full bg-white/90 dark:bg-slate-950/80 text-slate-800 dark:text-white backdrop-blur-md border border-slate-200 dark:border-slate-700 hover:bg-emerald-500 hover:text-white dark:hover:bg-emerald-600 transition-all shadow-xl cursor-pointer" aria-label="Previous screenshot">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <!-- Next Button -->
                <button type="button" id="page-gallery-next" class="absolute right-3 top-1/2 -translate-y-1/2 flex h-11 w-11 items-center justify-center rounded-full bg-white/90 dark:bg-slate-950/80 text-slate-800 dark:text-white backdrop-blur-md border border-slate-200 dark:border-slate-700 hover:bg-emerald-500 hover:text-white dark:hover:bg-emerald-600 transition-all shadow-xl cursor-pointer" aria-label="Next screenshot">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </button>
                @endif
            </div>

            <!-- Thumbnail Carousel Bar -->
            @if(count($galleryUrls) > 1)
            <div id="page-gallery-thumbs" class="grid grid-cols-4 sm:grid-cols-6 gap-2 p-1">
                @foreach($galleryUrls as $index => $gUrl)
                <button type="button" data-index="{{ $index }}" data-src="{{ $gUrl }}" class="page-thumb-btn aspect-video overflow-hidden rounded-xl border-2 {{ $index === 0 ? 'border-emerald-500 opacity-100 ring-2 ring-emerald-500/30' : 'border-transparent opacity-60' }} hover:opacity-100 transition-all cursor-pointer bg-slate-100 dark:bg-slate-950">
                    <img src="{{ $gUrl }}" alt="Screenshot {{ $index + 1 }}" class="w-full h-full object-cover">
                </button>
                @endforeach
            </div>
            @endif
        </div>
        @endif

        @if($project->video_url)
        <!-- Video Showcase -->
        <div class="mb-12 rounded-3xl overflow-hidden shadow-xl dark:shadow-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-3">
            <div class="relative aspect-video w-full overflow-hidden rounded-2xl bg-slate-950 border border-slate-200/60 dark:border-slate-800/80">
                <video controls class="w-full h-full object-contain">
                    <source src="{{ format_image_url($project->video_url) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
        @endif

        <!-- Case Study Details Grid -->
        <div class="space-y-10">

            <!-- Problem vs Solution Cards -->
            @if($project->problem || $project->solution)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($project->problem)
                <div class="rounded-2xl border border-amber-500/20 bg-amber-500/5 p-6 dark:bg-amber-950/10">
                    <div class="flex items-center gap-2 mb-3 text-amber-600 dark:text-amber-400 font-bold text-xs uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span>The Problem Statement</span>
                    </div>
                    <p class="text-sm sm:text-base text-slate-700 dark:text-slate-300 leading-relaxed">{{ $project->problem }}</p>
                </div>
                @endif

                @if($project->solution)
                <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/5 p-6 dark:bg-emerald-950/10">
                    <div class="flex items-center gap-2 mb-3 text-emerald-600 dark:text-emerald-400 font-bold text-xs uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                        <span>The Solution Architecture</span>
                    </div>
                    <p class="text-sm sm:text-base text-slate-700 dark:text-slate-300 leading-relaxed">{{ $project->solution }}</p>
                </div>
                @endif
            </div>
            @endif

            <!-- Main Narrative Description -->
            @if($project->description)
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/60 p-6 sm:p-8 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-3">Project Breakdown & Overview</h3>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line text-sm sm:text-base">{{ $project->description }}</p>
            </div>
            @endif

            <!-- Architecture Flowchart -->
            @if($project->architecture)
            @php
                $archSteps = array_filter(array_map('trim', preg_split('/->|→/', $project->architecture)));
            @endphp
            @if(count($archSteps) > 0)
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 sm:p-8 shadow-sm">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <h3 class="text-xs font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">System Data Flow & Architecture</h3>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    @foreach($archSteps as $idx => $step)
                    <span class="px-3.5 py-1.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 font-mono text-sm font-semibold">
                        {{ $step }}
                    </span>
                    @if($idx < count($archSteps) - 1)
                    <span class="text-slate-400 dark:text-slate-500 font-bold text-base">→</span>
                    @endif
                    @endforeach
                </div>
            </div>
            @endif
            @endif

            <!-- Key Features Grid -->
            @if($project->features && $project->features->count() > 0)
            <div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-4">Key Features & Capabilities</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($project->features as $feat)
                    <div class="flex items-start gap-3 p-4 rounded-xl border border-slate-200 dark:border-slate-800/80 bg-white dark:bg-slate-900/50 shadow-sm">
                        <svg class="w-5 h-5 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $feat->feature }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Tech Stack Pills -->
            @if($project->technologies && $project->technologies->count() > 0)
            <div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-4">Technologies & Stack Used</h3>
                <div class="flex flex-wrap gap-2.5">
                    @foreach($project->technologies as $tech)
                    <span class="px-4 py-2 rounded-xl text-xs font-mono font-bold border border-slate-200/80 dark:border-slate-800 bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200">
                        {{ $tech->name }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Challenges & Takeaways -->
            @if($project->challenges || $project->what_i_learned)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                @if($project->challenges)
                <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 p-6">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Technical Challenges Encountered</h4>
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">{{ $project->challenges }}</p>
                </div>
                @endif

                @if($project->what_i_learned)
                <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 p-6">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Key Takeaways & Impact</h4>
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">{{ $project->what_i_learned }}</p>
                </div>
                @endif
            </div>
            @endif

            <!-- Bottom Action Footer -->
            <div class="pt-8 border-t border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-4">
                <a href="{{ route('home') }}#projects" class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Return to Projects</span>
                </a>

                <div class="flex flex-wrap gap-3">
                    @if($project->live_demo_url || $project->demo_url)
                    <a href="{{ $project->live_demo_url ?? $project->demo_url }}" target="_blank" rel="noopener noreferrer" class="px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm transition-colors shadow">
                        Live Demo
                    </a>
                    @endif
                    @if($project->github_url)
                    <a href="{{ $project->github_url }}" target="_blank" rel="noopener noreferrer" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-white font-bold text-sm transition-colors border border-slate-200/80 dark:border-slate-700">
                        GitHub
                    </a>
                    @endif
                </div>
            </div>

        </div>
    </div>
</article>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const mainImg = document.getElementById('page-main-img');
    const prevBtn = document.getElementById('page-gallery-prev');
    const nextBtn = document.getElementById('page-gallery-next');
    const thumbBtns = document.querySelectorAll('.page-thumb-btn');

    if (!mainImg || thumbBtns.length === 0) return;

    let currentIndex = 0;
    const totalImgs = thumbBtns.length;

    const updateGallery = (idx) => {
        currentIndex = idx;
        const targetSrc = thumbBtns[currentIndex].getAttribute('data-src');
        if (targetSrc) {
            mainImg.style.opacity = '0.4';
            setTimeout(() => {
                mainImg.src = targetSrc;
                mainImg.style.opacity = '1';
            }, 100);
        }

        thumbBtns.forEach((btn, i) => {
            if (i === currentIndex) {
                btn.classList.add('border-emerald-500', 'opacity-100', 'ring-2', 'ring-emerald-500/30');
                btn.classList.remove('border-transparent', 'opacity-60');
            } else {
                btn.classList.remove('border-emerald-500', 'opacity-100', 'ring-2', 'ring-emerald-500/30');
                btn.classList.add('border-transparent', 'opacity-60');
            }
        });
    };

    thumbBtns.forEach((btn, idx) => {
        btn.addEventListener('click', () => updateGallery(idx));
    });

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            let newIdx = (currentIndex - 1 + totalImgs) % totalImgs;
            updateGallery(newIdx);
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            let newIdx = (currentIndex + 1) % totalImgs;
            updateGallery(newIdx);
        });
    }
});
</script>
@endpush
@endsection



