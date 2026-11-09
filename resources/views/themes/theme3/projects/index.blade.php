@extends('layouts.theme3')

@section('title', 'All Projects — Theme 3 (Glass)')

@section('content')
<section class="py-24 sm:py-28 relative min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Navigation Back Link -->
        <div class="mb-8">
            <a href="{{ route('home') }}#projects" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-cyan-400 hover:underline transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Back to Home</span>
            </a>
        </div>

        <!-- Page Header -->
        <div class="mb-12">
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 dark:border-cyan-500/20 text-blue-600 dark:text-cyan-400 text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-blue-500 dark:bg-cyan-400 animate-pulse"></span>
                <span>Engineering Archives</span>
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-3">
                All Projects &amp; Case Studies
            </h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300 max-w-2xl">
                Complete catalog of production web systems, Python REST microservices, AI integrations, and open-source software.
            </p>

            <!-- Category Filter Pills -->
            <div class="flex flex-wrap gap-2 mt-6">
                <a href="{{ route('projects.index') }}" class="px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider {{ !request('category') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'bg-white/80 dark:bg-slate-800/80 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 backdrop-blur-md hover:bg-white dark:hover:bg-slate-800' }}">
                    All
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('projects.index', ['category' => $cat->slug]) }}" class="px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider {{ request('category') === $cat->slug ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'bg-white/80 dark:bg-slate-800/80 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 backdrop-blur-md hover:bg-white dark:hover:bg-slate-800' }}">
                    {{ $cat->name }}
                </a>
                @endforeach
            </div>
        </div>

        <!-- Projects Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($projects as $proj)
            @php
                $img = $proj->cover_image ?? ($proj->featured_image ?? ($proj->images->first()?->image_path));
                $imgUrl = format_image_url($img);
                $videoUrl = format_image_url($proj->video_url);
            @endphp
            <div class="project-card-item group relative flex flex-col overflow-hidden rounded-3xl border border-white/80 dark:border-white/10 bg-white/70 dark:bg-slate-900/70 shadow-lg shadow-blue-500/5 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1.5 hover:border-blue-500/40 hover:shadow-2xl hover:shadow-blue-500/10 cursor-pointer"
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

                <!-- Thumbnail Image / Fallback Header -->
                <div class="relative aspect-video w-full overflow-hidden bg-slate-100 dark:bg-slate-800">
                    @if($imgUrl)
                    <img src="{{ $imgUrl }}" alt="{{ $proj->title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @else
                    <div class="w-full h-full p-6 flex flex-col justify-between bg-gradient-to-br from-blue-900/40 via-slate-900 to-indigo-950 text-white relative overflow-hidden">
                        <div class="flex items-center justify-between z-10">
                            <span class="text-[10px] font-mono tracking-wider text-cyan-300 uppercase">{{ $proj->category->name ?? 'Project' }}</span>
                            <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
                        </div>
                        <div class="z-10 text-center py-2">
                            <h4 class="text-base font-bold text-white line-clamp-1">{{ $proj->title }}</h4>
                        </div>
                        <div class="flex justify-between text-[10px] font-mono text-slate-300 z-10 border-t border-white/10 pt-2">
                            <span>Glass Architecture</span>
                            <span class="text-cyan-300">View Details →</span>
                        </div>
                    </div>
                    @endif

                    <!-- Category & Featured Badges -->
                    <div class="absolute top-3 left-3 right-3 flex items-center justify-between pointer-events-none z-10">
                        <div class="flex gap-1.5 flex-wrap">
                            @if(!empty($proj->featured))
                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-500/90 text-white px-2.5 py-0.5 text-[10px] font-bold shadow-md">
                                ★ Featured
                            </span>
                            @endif
                            @if($proj->category)
                            <span class="rounded-full bg-slate-900/80 text-slate-100 px-2.5 py-0.5 text-[10px] font-semibold backdrop-blur-md border border-white/10">
                                {{ $proj->category->name }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-cyan-400 transition-colors line-clamp-1">
                            {{ $proj->title }}
                        </h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300 line-clamp-2 mt-2 leading-relaxed">
                            {{ $proj->short_description ?? ($proj->summary ?? strip_tags($proj->description)) }}
                        </p>
                    </div>

                    <!-- Tech Stack Pills -->
                    @if($proj->technologies && $proj->technologies->count() > 0)
                    <div class="flex flex-wrap gap-1.5 pt-1">
                        @foreach($proj->technologies->take(4) as $tech)
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60">
                            {{ $tech->name }}
                        </span>
                        @endforeach
                    </div>
                    @endif

                    <!-- Action Link -->
                    <div class="pt-3 border-t border-slate-200/60 dark:border-slate-800/60 flex items-center justify-between">
                        <a href="{{ route('projects.show', $proj->slug) }}" class="text-xs font-bold text-blue-600 dark:text-cyan-400 hover:underline flex items-center gap-1">
                            <span>Case Study</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                        <button type="button" class="open-project-modal text-xs font-semibold text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-100">
                            Quick Preview ↗
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination Controls -->
        @if(method_exists($projects, 'links'))
        <div class="mt-12 flex justify-center">
            {{ $projects->links() }}
        </div>
        @endif

    </div>
</section>
@endsection

