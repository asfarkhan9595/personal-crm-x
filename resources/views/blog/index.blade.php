@extends('layouts.app')

@section('title', 'Blog & Technical Articles - ' . ($profile->name ?? 'Portfolio'))

@section('content')
<section class="py-20 sm:py-24 relative min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-12">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-xs font-bold uppercase tracking-widest mb-3">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Articles & Insights
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 sm:text-5xl tracking-tight">
                Technical Blog
            </h1>
            <p class="mt-4 text-base sm:text-lg text-slate-600 dark:text-slate-400">
                In-depth tutorials, architectural case studies, Python automation guides, and web development insights.
            </p>
        </div>

        <!-- Search & Filter Form -->
        <div class="max-w-3xl mx-auto mb-14">
            <form action="{{ route('blog.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 p-2 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-lg">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search articles by title, topic or tag..." class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-transparent text-sm text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-900 focus:border-emerald-500 focus:outline-none transition-all">
                </div>

                @if(isset($categories) && count($categories) > 0)
                <select name="category" class="px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-transparent text-sm text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-900 focus:border-emerald-500 focus:outline-none transition-all">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                @endif

                <button type="submit" class="px-6 py-3 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm transition-all shadow-md cursor-pointer">
                    Search
                </button>
            </form>

            @if(request('q') || request('category'))
            <div class="mt-3 text-center">
                <a href="{{ route('blog.index') }}" class="text-xs font-semibold text-slate-500 hover:text-emerald-500 underline">Clear search filters</a>
            </div>
            @endif
        </div>

        <!-- Posts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($posts as $post)
            @php
                $imgUrl = format_image_url($post->cover_image);
            @endphp
            <div class="group relative rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 overflow-hidden shadow-sm hover:shadow-xl hover:border-emerald-500/40 dark:hover:border-emerald-500/40 transition-all duration-300 flex flex-col">
                <!-- Cover Image / Fallback -->
                <a href="{{ route('blog.show', $post->slug) }}" class="relative aspect-video overflow-hidden bg-slate-950 block">
                    @if($imgUrl)
                    <img src="{{ $imgUrl }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent opacity-60"></div>
                    @else
                    <div class="w-full h-full p-6 flex flex-col justify-between bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-slate-800 via-slate-900 to-slate-950">
                        <div class="flex items-center justify-between text-[11px] font-mono text-emerald-400">
                            <span>ARTICLE // {{ strtoupper($post->category ?? 'TECH') }}</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        </div>
                        <h4 class="text-base font-bold text-slate-100 line-clamp-2 leading-snug group-hover:text-emerald-400 transition-colors">{{ $post->title }}</h4>
                        <div class="text-[11px] font-mono text-slate-400 border-t border-slate-800 pt-2 flex items-center justify-between">
                            <span>Read Article</span>
                            <span>→</span>
                        </div>
                    </div>
                    @endif

                    @if($post->category)
                    <span class="absolute top-3 left-3 bg-slate-950/80 text-emerald-400 border border-slate-700/60 backdrop-blur-md px-3 py-0.5 rounded-full text-xs font-semibold">
                        {{ $post->category }}
                    </span>
                    @endif
                </a>

                <!-- Card Body -->
                <div class="p-6 flex-1 flex flex-col">
                    <div class="flex items-center gap-3 text-xs text-slate-400 dark:text-slate-500 mb-2 font-mono">
                        <span>{{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('M d, Y') : $post->created_at->format('M d, Y') }}</span>
                        @if($post->read_time || $post->read_time_minutes)
                        <span>• {{ $post->read_time ?? $post->read_time_minutes }} min read</span>
                        @endif
                        @if($post->views_count)
                        <span>• {{ number_format($post->views_count) }} views</span>
                        @endif
                    </div>

                    <a href="{{ route('blog.show', $post->slug) }}" class="group/title">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 group-hover/title:text-emerald-500 transition-colors line-clamp-2 leading-snug">
                            {{ $post->title }}
                        </h3>
                    </a>

                    <p class="mt-2.5 text-sm text-slate-600 dark:text-slate-400 line-clamp-3 leading-relaxed flex-1">
                        {{ $post->excerpt }}
                    </p>

                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                        <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-500 hover:text-emerald-600 transition-colors">
                            <span>Read Article</span>
                            <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-20 bg-slate-50 dark:bg-slate-900/40 rounded-3xl border border-dashed border-slate-200 dark:border-slate-800">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                </div>
                <h3 class="text-base font-bold text-slate-700 dark:text-slate-300">No blog posts found</h3>
                <p class="text-xs text-slate-400 mt-1">Try adjusting your search keywords or selected category filter.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($posts->hasPages())
        <div class="mt-14 flex justify-center">
            {{ $posts->withQueryString()->links() }}
        </div>
        @endif
    </div>
</section>
@endsection


