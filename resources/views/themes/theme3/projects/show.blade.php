@extends('layouts.theme3')

@section('title', $project->title . ' — Theme 3 Case Study')
@section('meta_description', $project->short_description ?? ($project->summary ?? $project->description))

@section('content')
<article class="py-24 sm:py-28 relative min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Back Navigation -->
        <div class="mb-8">
            <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-cyan-400 hover:underline transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Back to All Projects</span>
            </a>
        </div>

        <!-- Case Study Header Card -->
        <header class="p-6 sm:p-10 rounded-3xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-2xl border border-white/80 dark:border-white/10 shadow-xl space-y-4 mb-8">
            <div class="flex flex-wrap items-center gap-2">
                @if($project->featured)
                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 dark:border-cyan-500/20 px-3 py-1 text-xs font-bold text-blue-600 dark:text-cyan-400 backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-blue-500 dark:bg-cyan-400 animate-pulse"></span>
                    Featured Project
                </span>
                @endif

                @if($project->category)
                <span class="rounded-full bg-white/80 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 px-3 py-1 text-xs font-semibold text-slate-700 dark:text-slate-300 backdrop-blur-md">
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
                <a href="{{ $project->live_demo_url ?? $project->demo_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-lg shadow-blue-500/25 transition-all hover:scale-105">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    <span>Launch Live Demo</span>
                </a>
                @endif

                @if($project->github_url)
                <a href="{{ $project->github_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white/80 dark:bg-slate-800/80 hover:bg-white dark:hover:bg-slate-800 text-slate-800 dark:text-white font-bold text-xs transition-all border border-slate-200 dark:border-slate-700 backdrop-blur-xl shadow-sm hover:scale-105">
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
        <div class="mb-10 p-2.5 rounded-3xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-2xl border border-white/80 dark:border-white/10 shadow-xl">
            <div class="relative aspect-video w-full overflow-hidden rounded-2xl bg-slate-950 flex items-center justify-center">
                <video controls class="w-full h-full object-contain rounded-2xl" preload="metadata">
                    <source src="{{ $pageVideoUrl }}" type="video/mp4">
                    Your browser does not support HTML5 video.
                </video>
            </div>
        </div>
        @endif

        @if(count($galleryUrls) > 0)
        <div id="page-gallery-wrapper-t3" class="mb-10 p-2.5 rounded-3xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-2xl border border-white/80 dark:border-white/10 shadow-xl space-y-2.5">
            <div class="relative aspect-video w-full overflow-hidden rounded-2xl bg-slate-950 flex items-center justify-center group">
                <img id="page-main-img-t3" src="{{ $mainImgUrl }}" alt="{{ $project->title }}" class="w-full h-full object-contain transition-opacity duration-300">
                
                @if(count($galleryUrls) > 1)
                <button type="button" id="page-gallery-prev-t3" class="absolute left-3 top-1/2 -translate-y-1/2 flex h-10 w-10 items-center justify-center rounded-full bg-white/80 dark:bg-slate-950/80 text-slate-800 dark:text-white backdrop-blur-md border border-white/40 dark:border-white/20 hover:bg-blue-600 hover:text-white transition-all shadow-xl cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button type="button" id="page-gallery-next-t3" class="absolute right-3 top-1/2 -translate-y-1/2 flex h-10 w-10 items-center justify-center rounded-full bg-white/80 dark:bg-slate-950/80 text-slate-800 dark:text-white backdrop-blur-md border border-white/40 dark:border-white/20 hover:bg-blue-600 hover:text-white transition-all shadow-xl cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </button>
                @endif
            </div>

            @if(count($galleryUrls) > 1)
            <div id="page-gallery-thumbs-t3" class="grid grid-cols-4 sm:grid-cols-6 gap-2 p-1">
                @foreach($galleryUrls as $index => $gUrl)
                <button type="button" data-index="{{ $index }}" data-src="{{ $gUrl }}" class="page-thumb-btn-t3 aspect-video overflow-hidden rounded-xl border-2 {{ $index === 0 ? 'border-blue-600 dark:border-cyan-400 opacity-100' : 'border-transparent opacity-60' }} hover:opacity-100 transition-all cursor-pointer bg-slate-950">
                    <img src="{{ $gUrl }}" alt="Thumbnail {{ $index + 1 }}" class="w-full h-full object-cover">
                </button>
                @endforeach
            </div>
            @endif
        </div>
        @endif

        <!-- Case Study Detailed Content Body -->
        <div class="p-6 sm:p-10 rounded-3xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-2xl border border-white/80 dark:border-white/10 shadow-xl space-y-8">
            
            @if($project->problem)
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    The Problem &amp; Challenge
                </h3>
                <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                    {{ $project->problem }}
                </p>
            </div>
            @endif

            @if($project->solution)
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    The Solution &amp; Engineering Approach
                </h3>
                <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                    {{ $project->solution }}
                </p>
            </div>
            @endif

            @if($project->description)
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Detailed Overview</h3>
                <div class="prose dark:prose-invert max-w-none text-sm text-slate-600 dark:text-slate-300 leading-relaxed space-y-3">
                    {!! nl2br(e($project->description)) !!}
                </div>
            </div>
            @endif

            @if($project->architecture)
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">System Architecture &amp; Data Flow</h3>
                <div class="p-4 rounded-2xl bg-slate-100 dark:bg-slate-800/80 font-mono text-xs text-blue-600 dark:text-cyan-400 border border-slate-200 dark:border-slate-700">
                    {{ $project->architecture }}
                </div>
            </div>
            @endif

            @if($project->features && $project->features->count() > 0)
            <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Key Features &amp; Capabilities</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    @foreach($project->features as $feat)
                    <div class="flex items-center gap-2.5 p-3 rounded-2xl bg-white/60 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs">
                        <svg class="w-4 h-4 text-blue-600 dark:text-cyan-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        <span>{{ $feat->feature }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Tech Stack Badges -->
            @if($project->technologies && $project->technologies->count() > 0)
            <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Technologies Used</h4>
                <div class="flex flex-wrap gap-2">
                    @foreach($project->technologies as $tech)
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/10 dark:bg-cyan-500/10 text-blue-600 dark:text-cyan-400 border border-blue-500/20 dark:border-cyan-500/20">
                        {{ $tech->name }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            @if($project->challenges || $project->what_i_learned)
            <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-4">
                @if($project->challenges)
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400 mb-1">Technical Challenges</h4>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">{{ $project->challenges }}</p>
                </div>
                @endif

                @if($project->what_i_learned)
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-1">Key Takeaways &amp; Impact</h4>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">{{ $project->what_i_learned }}</p>
                </div>
                @endif
            </div>
            @endif

        </div>

    </div>
</article>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const mainImg = document.getElementById('page-main-img-t3');
    const thumbs = document.querySelectorAll('.page-thumb-btn-t3');
    const prevBtn = document.getElementById('page-gallery-prev-t3');
    const nextBtn = document.getElementById('page-gallery-next-t3');

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
