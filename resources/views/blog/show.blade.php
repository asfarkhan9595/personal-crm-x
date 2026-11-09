@extends('layouts.app')

@section('title', $post->title . ' - Blog')
@section('meta_description', $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->content), 160))

@section('content')
<article class="py-20 sm:py-24 relative min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Navigation Back Link -->
        <div class="mb-8">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-emerald-500 hover:text-emerald-600 dark:text-emerald-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Back to All Articles</span>
            </a>
        </div>

        <!-- Article Header -->
        <header class="space-y-4 mb-10">
            @if($post->category)
            <span class="inline-block text-xs font-bold uppercase tracking-wider text-emerald-500 bg-emerald-500/10 border border-emerald-500/20 px-3.5 py-1 rounded-full">
                {{ $post->category }}
            </span>
            @endif

            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight leading-tight">
                {{ $post->title }}
            </h1>

            @if($post->excerpt)
            <p class="text-lg text-slate-600 dark:text-slate-300 leading-relaxed font-medium">
                {{ $post->excerpt }}
            </p>
            @endif

            <!-- Metadata bar -->
            <div class="flex flex-wrap items-center justify-between gap-4 text-xs text-slate-500 dark:text-slate-400 font-mono pt-4 border-t border-slate-200/80 dark:border-slate-800">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="font-semibold text-slate-700 dark:text-slate-300">
                        {{ $post->author ?? ($profile->name ?? 'Author') }}
                    </span>
                    <span>•</span>
                    <span>Published {{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('F d, Y') : $post->created_at->format('F d, Y') }}</span>
                    @if($post->read_time || $post->read_time_minutes)
                    <span>• {{ $post->read_time ?? $post->read_time_minutes }} min read</span>
                    @endif
                </div>

                @if($post->views_count)
                <div class="flex items-center gap-1.5 text-emerald-500 font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <span>{{ number_format($post->views_count) }} views</span>
                </div>
                @endif
            </div>
        </header>

        <!-- Cover Image -->
        @php
            $coverUrl = format_image_url($post->cover_image);
        @endphp
        @if($coverUrl)
        <div class="mb-12 rounded-3xl overflow-hidden shadow-xl dark:shadow-2xl border border-slate-200 dark:border-slate-800 bg-slate-950 p-2">
            <div class="aspect-video w-full overflow-hidden rounded-2xl bg-slate-900 flex items-center justify-center">
                <img src="{{ $coverUrl }}" alt="{{ $post->title }}" class="w-full h-full object-contain">
            </div>
        </div>
        @endif

        <!-- Article Content Body -->
        <div class="rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-slate-900/80 p-6 sm:p-10 shadow-sm">
            <div class="prose dark:prose-invert max-w-none text-slate-700 dark:text-slate-300 leading-relaxed text-base sm:text-lg space-y-6">
                {!! $post->content !!}
            </div>
        </div>

        <!-- Author Bio Box -->
        @if(isset($profile))
        <div class="mt-12 p-6 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800 flex items-center gap-4">
            @if($profile->avatar)
            <img src="{{ format_image_url($profile->avatar) }}" alt="{{ $profile->name }}" class="w-14 h-14 rounded-full object-cover border-2 border-emerald-500">
            @else
            <div class="w-14 h-14 rounded-full bg-emerald-500/10 border-2 border-emerald-500 flex items-center justify-center font-bold text-emerald-500 text-lg">
                {{ substr($profile->name ?? 'A', 0, 1) }}
            </div>
            @endif
            <div>
                <h4 class="font-bold text-slate-900 dark:text-slate-100 text-base">{{ $profile->name }}</h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $profile->title ?? 'Full-Stack Developer & Software Engineer' }}</p>
                <p class="text-xs text-slate-600 dark:text-slate-300 mt-1 line-clamp-2">{{ $profile->bio }}</p>
            </div>
        </div>
        @endif

        <!-- Related Posts Section -->
        @if(isset($relatedPosts) && $relatedPosts->count() > 0)
        <section class="mt-16 pt-12 border-t border-slate-200 dark:border-slate-800">
            <h3 class="text-2xl font-extrabold text-slate-900 dark:text-slate-100 mb-8 tracking-tight">Related Articles</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedPosts as $rel)
                @php
                    $relImg = format_image_url($rel->cover_image);
                @endphp
                <a href="{{ route('blog.show', $rel->slug) }}" class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-sm hover:shadow-xl hover:border-emerald-500/40 transition-all duration-300 flex flex-col group">
                    <div class="aspect-video w-full overflow-hidden bg-slate-950">
                        @if($relImg)
                        <img src="{{ $relImg }}" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                        <div class="w-full h-full p-4 flex items-center justify-center bg-slate-900 text-xs font-mono text-emerald-400">
                            {{ $rel->category ?? 'Article' }}
                        </div>
                        @endif
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-emerald-500 mb-1.5 block">{{ $rel->category ?? 'Article' }}</span>
                        <h4 class="font-bold text-sm text-slate-900 dark:text-slate-100 group-hover:text-emerald-500 transition-colors line-clamp-2 leading-snug">{{ $rel->title }}</h4>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif

    </div>
</article>
@endsection


