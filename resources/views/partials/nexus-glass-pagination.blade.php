@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center gap-2 mt-12">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-3.5 py-2 rounded-xl bg-white/20 dark:bg-slate-900/20 border border-white/30 dark:border-white/5 text-slate-400 dark:text-slate-600 text-xs font-semibold cursor-not-allowed flex items-center gap-1 opacity-50 select-none">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                <span>Prev</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-3.5 py-2 rounded-xl bg-white/60 dark:bg-slate-800/60 border border-white/80 dark:border-white/10 text-slate-800 dark:text-slate-200 hover:text-blue-600 dark:hover:text-cyan-400 text-xs font-semibold flex items-center gap-1 backdrop-blur-md shadow-md transition-all hover:scale-105">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                <span>Prev</span>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="px-2 py-2 text-xs text-slate-400 font-mono">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-extrabold text-xs shadow-lg shadow-blue-500/25 cursor-default">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="px-3.5 py-2 rounded-xl bg-white/60 dark:bg-slate-800/60 border border-white/80 dark:border-white/10 text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-cyan-400 text-xs font-semibold backdrop-blur-md transition-all hover:scale-105">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-3.5 py-2 rounded-xl bg-white/60 dark:bg-slate-800/60 border border-white/80 dark:border-white/10 text-slate-800 dark:text-slate-200 hover:text-blue-600 dark:hover:text-cyan-400 text-xs font-semibold flex items-center gap-1 backdrop-blur-md shadow-md transition-all hover:scale-105">
                <span>Next</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
            </a>
        @else
            <span class="px-3.5 py-2 rounded-xl bg-white/20 dark:bg-slate-900/20 border border-white/30 dark:border-white/5 text-slate-400 dark:text-slate-600 text-xs font-semibold cursor-not-allowed flex items-center gap-1 opacity-50 select-none">
                <span>Next</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
            </span>
        @endif
    </nav>
@endif

