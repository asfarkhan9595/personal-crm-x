<section id="blog" class="py-20 relative bg-slate-100/50 dark:bg-slate-900/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-xs font-bold uppercase tracking-widest">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Articles & Insights
                </div>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 mt-3 sm:text-4xl tracking-tight">
                    Latest Blog Posts
                </h2>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 max-w-xl">
                    Articles on Laravel engineering, Python automation, AI API integrations, and backend architecture.
                </p>
            </div>

            <a href="{{ route('blog.index') }}" class="mt-4 md:mt-0 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-emerald-500 hover:text-emerald-600 dark:text-emerald-400 transition-colors">
                <span>Browse All Articles</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>

        <!-- Posts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($posts as $post)
            @php
                $imgUrl = format_image_url($post->cover_image);
            @endphp
            <div class="group relative rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 overflow-hidden shadow-sm hover:shadow-xl hover:border-emerald-500/40 dark:hover:border-emerald-500/40 transition-all duration-300 flex flex-col">
                <!-- Cover Image / Fallback Header -->
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
                            <span>Read Case Study</span>
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

                <!-- Post Content Body -->
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
            @endforeach
        </div>
    </div>
</section>


