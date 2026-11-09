@extends('layouts.theme1')

@section('title', $post->title . ' — Theme 1 Blog')
@section('meta_description', $post->excerpt ?? ($post->summary ?? strip_tags($post->content)))

@section('content')
<article class="py-20 sm:py-24 relative min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Back Link -->
        <div class="mb-8">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-cyan-400 hover:underline transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Back to All Articles</span>
            </a>
        </div>

        <!-- Article Header Card -->
        <header class="p-8 sm:p-10 rounded-3xl nexus-glass-card space-y-4 mb-10">
            <div class="flex flex-wrap items-center gap-3 text-xs font-mono text-slate-500 dark:text-slate-400">
                <span class="text-blue-600 dark:text-cyan-400 font-bold uppercase">{{ $post->category ?? 'Engineering' }}</span>
                <span>•</span>
                <span>{{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('M d, Y') : $post->created_at->format('M d, Y') }}</span>
                <span>•</span>
                <span>{{ $post->reading_time ?? '5 min read' }}</span>
                <span>•</span>
                <span>{{ $post->views_count ?? 0 }} views</span>
            </div>

            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                {{ $post->title }}
            </h1>

            @if($post->excerpt)
            <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 font-normal leading-relaxed">
                {{ $post->excerpt }}
            </p>
            @endif
        </header>

        <!-- Article Content Card -->
        <div class="p-8 sm:p-12 rounded-3xl nexus-glass-card prose dark:prose-invert max-w-none text-slate-700 dark:text-slate-300 text-sm leading-relaxed mb-12">
            {!! nl2br(e($post->content)) !!}
        </div>

        <!-- Related Posts Section -->
        @if(isset($relatedPosts) && count($relatedPosts))
        <div class="mt-16 space-y-6">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white font-mono">// RELATED ARTICLES</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedPosts as $rel)
                <div class="p-5 rounded-2xl nexus-glass-card space-y-2">
                    <span class="text-[10px] font-mono text-blue-600 dark:text-cyan-400 font-bold uppercase">{{ $rel->category ?? 'Tech' }}</span>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white line-clamp-2">
                        <a href="{{ route('blog.show', $rel->slug) }}" class="hover:underline">
                            {{ $rel->title }}
                        </a>
                    </h3>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</article>
@endsection

