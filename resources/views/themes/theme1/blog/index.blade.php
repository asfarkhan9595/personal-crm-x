@extends('layouts.theme1')

@section('title', 'Blog & Tech Articles — Theme 1')

@section('content')
<section class="py-20 sm:py-24 relative min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Back Navigation -->
        <div class="mb-8">
            <a href="{{ route('home') }}#blog" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-cyan-400 hover:underline transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Back to Home</span>
            </a>
        </div>

        <!-- Page Header -->
        <div class="mb-12">
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 dark:border-cyan-500/20 text-blue-600 dark:text-cyan-400 text-xs font-semibold uppercase tracking-wider backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-blue-500 dark:bg-cyan-400 animate-pulse"></span>
                <span>Engineering Journal</span>
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-3">
                Articles &amp; Tech Tutorials
            </h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 max-w-2xl">
                Deep dives into full-stack architecture, Python microservices, AI integrations, Laravel APIs, and software engineering practices.
            </p>

            <!-- Search & Filters Container (Stacked on Separate Lines) -->
            <div class="mt-8 space-y-4">
                
                <!-- Line 1: Unified Glass Search Bar (AJAX Enabled) -->
                <form id="blog-search-form" action="{{ route('blog.index') }}" method="GET" class="relative w-full max-w-xl">
                    <div class="relative flex items-center">
                        <svg class="w-4 h-4 absolute left-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" id="blog-search-input" name="q" value="{{ request('q') }}" placeholder="Search articles by title, keyword, or topic..."
                               class="w-full rounded-2xl bg-white/60 dark:bg-slate-900/60 border border-white/80 dark:border-white/12 pl-11 pr-24 py-3.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 dark:focus:border-cyan-400 focus:outline-none backdrop-blur-md shadow-sm transition-all">
                        
                        <!-- Search Spinner Indicator -->
                        <div id="search-spinner" class="hidden absolute right-24 text-blue-600 dark:text-cyan-400 animate-spin">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>

                        <button type="submit" class="absolute right-1.5 px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xs shadow-md transition-all hover:scale-105 cursor-pointer">
                            Search
                        </button>
                    </div>
                </form>

                <!-- Line 2: Category Filter Pills (AJAX Enabled) -->
                @if(isset($categories) && count($categories) > 0)
                <div id="blog-category-filters" class="flex flex-wrap gap-2 items-center pt-1">
                    <button type="button" data-category="" class="blog-cat-pill px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all cursor-pointer {{ !request('category') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-500/25 active-cat' : 'bg-white/60 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 border border-white/80 dark:border-white/12 backdrop-blur-md hover:bg-white dark:hover:bg-slate-800' }}">
                        All
                    </button>
                    @foreach($categories as $cat)
                    <button type="button" data-category="{{ $cat }}" class="blog-cat-pill px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all cursor-pointer {{ request('category') === $cat ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-500/25 active-cat' : 'bg-white/60 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 border border-white/80 dark:border-white/12 backdrop-blur-md hover:bg-white dark:hover:bg-slate-800' }}">
                        {{ $cat }}
                    </button>
                    @endforeach
                </div>
                @endif

            </div>
        </div>

        <!-- Dynamic AJAX Blog Container -->
        <div id="blog-grid-container" class="transition-opacity duration-300">
            @include('themes.theme1.blog.partials.posts-grid', ['posts' => $posts])
        </div>

    </div>
</section>

<!-- AJAX Live Search Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchForm = document.getElementById('blog-search-form');
    const searchInput = document.getElementById('blog-search-input');
    const spinner = document.getElementById('search-spinner');
    const container = document.getElementById('blog-grid-container');
    const categoryContainer = document.getElementById('blog-category-filters');

    let currentCategory = "{{ request('category', '') }}";
    let searchDebounceTimer = null;

    function activeCategoryButton(catVal) {
        if (!categoryContainer) return;
        const buttons = categoryContainer.querySelectorAll('.blog-cat-pill');
        buttons.forEach(btn => {
            const btnCat = btn.getAttribute('data-category');
            if (btnCat === catVal) {
                btn.className = 'blog-cat-pill px-3.5 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all cursor-pointer bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-500/25 active-cat';
            } else {
                btn.className = 'blog-cat-pill px-3.5 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all cursor-pointer bg-white/60 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 border border-white/80 dark:border-white/12 backdrop-blur-md hover:bg-white dark:hover:bg-slate-800';
            }
        });
    }

    function fetchBlogPosts(urlOverride) {
        if (spinner) spinner.classList.remove('hidden');
        if (container) container.classList.add('opacity-40');

        let targetUrl = urlOverride;
        if (!targetUrl) {
            const query = encodeURIComponent(searchInput ? searchInput.value.trim() : '');
            const cat = encodeURIComponent(currentCategory);
            targetUrl = `{{ route('blog.index') }}?q=${query}&category=${cat}&ajax=1`;
        } else {
            // Append ajax parameter if not present
            if (targetUrl.indexOf('ajax=1') === -1) {
                targetUrl += (targetUrl.indexOf('?') === -1 ? '?' : '&') + 'ajax=1';
            }
        }

        fetch(targetUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.html !== undefined) {
                container.innerHTML = data.html;
            }
        })
        .catch(err => console.error('AJAX fetch error:', err))
        .finally(() => {
            if (spinner) spinner.classList.add('hidden');
            if (container) container.classList.remove('opacity-40');
        });
    }

    // 1. Live Search Input Listener with 300ms Debounce
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(searchDebounceTimer);
            searchDebounceTimer = setTimeout(() => {
                fetchBlogPosts();
            }, 300);
        });
    }

    // 2. Prevent Form Reload on Submit
    if (searchForm) {
        searchForm.addEventListener('submit', function (e) {
            e.preventDefault();
            clearTimeout(searchDebounceTimer);
            fetchBlogPosts();
        });
    }

    // 3. Category Filter Pill Click Listeners
    if (categoryContainer) {
        categoryContainer.addEventListener('click', function (e) {
            const btn = e.target.closest('.blog-cat-pill');
            if (!btn) return;
            e.preventDefault();
            currentCategory = btn.getAttribute('data-category') || '';
            activeCategoryButton(currentCategory);
            fetchBlogPosts();
        });
    }

    // 4. Delegated Event Listener for AJAX Pagination & Reset Button
    document.addEventListener('click', function (e) {
        // Reset button
        if (e.target.closest('#reset-blog-filters-btn')) {
            e.preventDefault();
            if (searchInput) searchInput.value = '';
            currentCategory = '';
            activeCategoryButton('');
            fetchBlogPosts();
            return;
        }

        // Pagination links inside container
        const pagLink = e.target.closest('#blog-grid-container .pagination a, #blog-grid-container nav a');
        if (pagLink) {
            e.preventDefault();
            const href = pagLink.getAttribute('href');
            if (href && href !== '#') {
                fetchBlogPosts(href);
                window.scrollTo({
                    top: container.offsetTop - 120,
                    behavior: 'smooth'
                });
            }
        }
    });
});
</script>
@endsection
