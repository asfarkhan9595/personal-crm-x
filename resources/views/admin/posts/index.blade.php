@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header Bar -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                <i data-lucide="newspaper" class="w-7 h-7 text-indigo-600 dark:text-indigo-400"></i>
                Daily Blog Posts Management
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage technical blog articles, daily tutorials, and engineering notes.</p>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition-colors">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Create New Post
        </a>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-200 dark:border-gray-700 mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
        <form action="{{ route('admin.posts.index') }}" method="GET" class="flex items-center gap-3 w-full sm:w-auto">
            <div class="relative w-full sm:w-80">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-3 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts..." class="w-full pl-9 pr-4 py-2 text-xs rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
            </div>
            <select name="status" onchange="this.form.submit()" class="px-3 py-2 text-xs rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option value="">All Statuses</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
        </form>
        <span class="text-xs text-gray-500 dark:text-gray-400">Total Posts: <strong>{{ $posts->total() }}</strong></span>
    </div>

    <!-- Posts Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        @if($posts->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 uppercase text-[10px] font-bold tracking-wider text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-4">Article</th>
                            <th class="px-4 py-4">Category</th>
                            <th class="px-4 py-4">Status</th>
                            <th class="px-4 py-4">Published At</th>
                            <th class="px-4 py-4">Views</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($posts as $post)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($post->cover_image)
                                            <img src="{{ format_image_url($post->cover_image) }}" alt="" class="w-12 h-12 rounded-lg object-cover border border-gray-200 dark:border-gray-700">
                                        @else
                                            <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                                                <i data-lucide="image" class="w-5 h-5"></i>
                                            </div>
                                        @endif
                                            <div class="flex items-center gap-1.5 flex-wrap">
                                                <h3 class="font-bold text-gray-900 dark:text-white text-sm line-clamp-1">{{ $post->title }}</h3>
                                                @if($post->is_featured)
                                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">Featured</span>
                                                @endif
                                                @if($post->is_trending)
                                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">🔥 Trending</span>
                                                @endif
                                            </div>
                                            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-mono">
                                                {{ $post->read_time }} • {{ $post->author ?: 'Asfar' }}
                                                @if($post->source_url)
                                                    • <a href="{{ $post->source_url }}" target="_blank" class="text-indigo-500 hover:underline">Source ↗</a>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 whitespace-nowrap inline-block">
                                        {{ $post->category }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <form action="{{ route('admin.posts.toggle-publish', $post->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold transition-all {{ $post->is_published ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $post->is_published ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                            {{ $post->is_published ? 'Published' : 'Draft' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-4 py-4 font-mono text-[11px]">
                                    {{ $post->published_at ? $post->published_at->format('M d, Y') : '—' }}
                                </td>
                                <td class="px-4 py-4 font-mono text-[11px]">
                                    {{ number_format($post->views_count) }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.posts.edit', $post->id) }}" class="p-1.5 text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" title="Edit Post">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this blog post?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="Delete Post">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                {{ $posts->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <i data-lucide="newspaper" class="w-12 h-12 mx-auto text-gray-400 mb-3"></i>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">No Blog Posts Found</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-4">Start publishing daily technical notes and architecture insights.</p>
                <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow">
                    <i data-lucide="plus" class="w-4 h-4 mr-1.5"></i> Create First Post
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

