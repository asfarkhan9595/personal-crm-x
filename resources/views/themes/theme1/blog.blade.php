<section id="blog" class="py-12 sm:py-16 relative">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 dark:border-cyan-500/20 text-blue-600 dark:text-cyan-400 text-[11px] font-semibold uppercase tracking-wider backdrop-blur-md">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 dark:bg-cyan-400 animate-pulse"></span>
                    <span>Knowledge</span>
                </div>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-2">
                    Latest Blog Posts
                </h2>
            </div>

            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white/60 dark:bg-slate-800/60 hover:bg-white dark:hover:bg-slate-800 text-slate-800 dark:text-slate-200 border border-white/70 dark:border-white/10 text-xs font-bold transition-all shadow-2xs">
                <span>View All Articles</span>
                <svg class="w-3.5 h-3.5 text-blue-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>

        <!-- Glass Blog Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($posts as $post)
            <article class="p-5 rounded-2xl nexus-glass-card group flex flex-col transition-all duration-300">
                <div class="flex items-center justify-between text-[11px] font-mono text-slate-500 dark:text-slate-400 mb-2">
                    <span>{{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('M d, Y') : $post->created_at->format('M d, Y') }}</span>
                    <span class="text-blue-600 dark:text-cyan-400 font-semibold">{{ $post->reading_time ?? '5 min read' }}</span>
                </div>

                <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-cyan-400 transition-colors mb-2 line-clamp-2">
                    <a href="{{ route('blog.show', $post->slug) }}">
                        {{ $post->title }}
                    </a>
                </h3>

                <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300 line-clamp-2 mb-4">
                    {{ $post->excerpt ?? ($post->summary ?? strip_tags($post->content)) }}
                </p>

                <div class="mt-auto pt-3 border-t border-white/40 dark:border-white/10 flex items-center justify-between text-xs font-bold">
                    <a href="{{ route('blog.show', $post->slug) }}" class="text-blue-600 dark:text-cyan-400 group-hover:underline inline-flex items-center gap-1">
                        <span>Read More</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </article>
            @endforeach
        </div>

    </div>
</section>
