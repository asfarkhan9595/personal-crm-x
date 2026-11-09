@if($posts->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @foreach($posts as $post)
    <article class="group relative flex flex-col overflow-hidden rounded-2xl nexus-glass-card border border-white/60 dark:border-white/10 transition-all duration-300 hover:-translate-y-1.5 hover:border-blue-500/60 dark:hover:border-cyan-400/60 shadow-lg hover:shadow-2xl backdrop-blur-xl">
        
        <!-- Blog Header Frame (Cover Image or Glass Banner) -->
        <div class="relative aspect-[16/9] w-full overflow-hidden bg-slate-950/80 border-b border-white/10">
            @if(!empty($post->cover_image))
                <img src="{{ format_image_url($post->cover_image) }}"
                     alt="{{ $post->title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
            @else
                <div class="relative inset-0 w-full h-full p-4 flex flex-col justify-between bg-gradient-to-br from-indigo-950 via-slate-900 to-slate-950 group-hover:from-blue-900 group-hover:to-slate-900 transition-colors duration-500">
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-[10px] text-cyan-400 font-semibold uppercase">// TECH ARTICLE</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                    </div>
                    <div class="py-2">
                        <h4 class="text-xs font-bold text-slate-300 font-mono line-clamp-1">&gt; {{ $post->category ?? 'Software Architecture' }}</h4>
                    </div>
                    <div class="flex items-center justify-between text-[10px] font-mono text-slate-400 border-t border-white/10 pt-1.5">
                        <span>Published</span>
                        <span class="text-cyan-400 font-bold">Read &rarr;</span>
                    </div>
                </div>
            @endif

            <!-- Floating Badges -->
            <div class="absolute top-2.5 left-2.5 flex items-center gap-1.5 z-20 pointer-events-none">
                @if($post->is_featured)
                <span class="inline-flex items-center gap-1 rounded-full bg-amber-500/20 border border-amber-500/40 text-amber-300 px-2.5 py-0.5 text-[10px] font-extrabold backdrop-blur-md">
                    <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    Featured
                </span>
                @endif

                @if(!empty($post->category))
                <span class="rounded-full bg-slate-900/60 border border-white/20 text-slate-100 px-2.5 py-0.5 text-[10px] font-bold backdrop-blur-md">
                    {{ $post->category }}
                </span>
                @endif
            </div>
        </div>

        <!-- Glass Card Body -->
        <div class="flex flex-1 flex-col p-5">
            <div class="flex items-center justify-between text-[11px] font-mono text-slate-500 dark:text-slate-400 mb-2">
                <span>{{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('M d, Y') : $post->created_at->format('M d, Y') }}</span>
                <span class="text-blue-600 dark:text-cyan-400 font-semibold">{{ $post->read_time ?? '5 min read' }}</span>
            </div>

            <h2 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-cyan-400 transition-colors line-clamp-2 mb-2">
                <a href="{{ route('blog.show', $post->slug) }}">
                    {{ $post->title }}
                </a>
            </h2>

            <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300 line-clamp-3 mb-4">
                {{ $post->excerpt ?? ($post->summary ?? strip_tags($post->content)) }}
            </p>

            <div class="mt-auto pt-3 border-t border-white/40 dark:border-white/10 flex items-center justify-between text-xs font-bold">
                <a href="{{ route('blog.show', $post->slug) }}" class="text-blue-600 dark:text-cyan-400 group-hover:underline inline-flex items-center gap-1">
                    <span>Read Full Article</span>
                    <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>

    </article>
    @endforeach
</div>

<!-- Custom Nexus Glass Pagination -->
@if(method_exists($posts, 'links'))
    <div class="blog-ajax-pagination">
        {{ $posts->links('partials.nexus-glass-pagination') }}
    </div>
@endif

@else
<div class="p-12 rounded-3xl nexus-glass-card text-center max-w-xl mx-auto my-12">
    <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
    <h3 class="text-lg font-extrabold text-slate-900 dark:text-white mb-1">No Articles Found</h3>
    <p class="text-xs text-slate-600 dark:text-slate-400 mb-6">No articles matched your search term or category filter.</p>
    <button type="button" id="reset-blog-filters-btn" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-xs shadow-md cursor-pointer">
        Reset Search Filters
    </button>
</div>
@endif

