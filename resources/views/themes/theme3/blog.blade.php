<section id="blog" class="py-20 sm:py-24 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 dark:border-cyan-500/20 text-blue-600 dark:text-cyan-400 text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-blue-500 dark:bg-cyan-400 animate-pulse"></span>
                    <span>Tech Writing &amp; Articles</span>
                </span>
                <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-3">
                    Latest Blog Posts
                </h2>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300 max-w-xl">
                    Articles on web architecture, Python microservices, Laravel backend tips, and AI workflows.
                </p>
            </div>

            <!-- View All Articles Link -->
            <div class="mt-6 md:mt-0">
                <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-cyan-400 hover:underline transition-colors">
                    <span>Explore All Articles</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>

        @if(isset($posts) && $posts->count() > 0)
        <!-- Blog Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($posts->take(3) as $post)
            <article class="group relative flex flex-col justify-between overflow-hidden rounded-3xl border border-white/80 dark:border-white/10 bg-white/70 dark:bg-slate-900/70 shadow-lg shadow-blue-500/5 backdrop-blur-xl p-6 transition-all duration-300 hover:-translate-y-1.5 hover:border-blue-500/40 hover:shadow-2xl hover:shadow-blue-500/10">
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-xs font-semibold text-blue-600 dark:text-cyan-400">
                        <span class="px-2.5 py-0.5 rounded-full bg-blue-500/10 border border-blue-500/20">{{ $post->category ?? 'Tech' }}</span>
                        <span class="text-slate-400">{{ optional($post->published_at ?? $post->created_at)->format('M d, Y') }}</span>
                    </div>

                    <h3 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-cyan-400 transition-colors line-clamp-2">
                        <a href="{{ route('blog.show', $post->slug) }}">
                            {{ $post->title }}
                        </a>
                    </h3>

                    <p class="text-xs text-slate-600 dark:text-slate-300 line-clamp-3 leading-relaxed">
                        {{ $post->excerpt ?? strip_tags($post->content) }}
                    </p>
                </div>

                <div class="pt-4 mt-6 border-t border-slate-200/60 dark:border-slate-800/60 flex items-center justify-between text-xs font-semibold">
                    <span class="text-slate-400">{{ $post->views_count ?? 0 }} reads</span>
                    <a href="{{ route('blog.show', $post->slug) }}" class="text-blue-600 dark:text-cyan-400 hover:underline flex items-center gap-1">
                        <span>Read Article</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </article>
            @endforeach
        </div>
        @else
        <div class="text-center p-12 rounded-3xl bg-white/70 dark:bg-slate-900/70 border border-white/80 dark:border-white/10 backdrop-blur-xl text-slate-500 text-xs">
            No articles published yet. Check back soon!
        </div>
        @endif

    </div>
</section>
