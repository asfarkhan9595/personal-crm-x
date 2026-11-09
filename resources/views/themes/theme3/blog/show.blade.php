@extends('layouts.theme3')

@section('title', $post->title . ' — Theme 3 Blog')
@section('meta_description', $post->excerpt ?? strip_tags($post->content))

@section('content')
<article class="py-24 sm:py-28 relative min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Back Link -->
        <div class="mb-8">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-cyan-400 hover:underline transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Back to All Articles</span>
            </a>
        </div>

        <!-- Post Header -->
        <header class="p-6 sm:p-10 rounded-3xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-2xl border border-white/80 dark:border-white/10 shadow-xl space-y-4 mb-8">
            <div class="flex items-center gap-3 text-xs font-semibold text-blue-600 dark:text-cyan-400">
                <span class="px-3 py-1 rounded-full bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 uppercase font-bold">
                    {{ $post->category ?? 'Tech' }}
                </span>
                <span class="text-slate-400">•</span>
                <span class="text-slate-400">{{ optional($post->published_at ?? $post->created_at)->format('M d, Y') }}</span>
                <span class="text-slate-400">•</span>
                <span class="text-slate-400">{{ $post->views_count ?? 0 }} views</span>
            </div>

            <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                {{ $post->title }}
            </h1>

            @if($post->excerpt)
            <p class="text-base text-slate-600 dark:text-slate-300 leading-relaxed italic">
                "{{ $post->excerpt }}"
            </p>
            @endif
        </header>

        <!-- Post Content Body -->
        <div class="p-6 sm:p-10 rounded-3xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-2xl border border-white/80 dark:border-white/10 shadow-xl prose dark:prose-invert max-w-none text-slate-700 dark:text-slate-300 text-sm leading-relaxed mb-12">
            {!! $post->content !!}
        </div>

    </div>
</article>
@endsection

