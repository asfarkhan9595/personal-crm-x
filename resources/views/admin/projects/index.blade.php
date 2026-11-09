@extends('layouts.admin')
@section('content')
<div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Projects</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your portfolio projects and case studies.</p>
    </div>
    <a href="{{ route('admin.projects.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-lg shadow-indigo-500/20">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add Project
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 flex items-center gap-3">
        <i data-lucide="check-circle" class="w-5 h-5"></i>
        {{ session('success') }}
    </div>
@endif

<div class="bg-white dark:bg-gray-800/80 backdrop-blur-sm shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Project Details</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($items as $item)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            @php
                                $thumbUrl = $item->cover_image ? format_image_url($item->cover_image) : ($item->images && $item->images->count() > 0 ? format_image_url($item->images->first()->image_path) : null);
                            @endphp
                            @if($thumbUrl)
                                <img src="{{ $thumbUrl }}" alt="{{ $item->title }}" class="w-10 h-10 rounded-lg object-cover border border-gray-200 dark:border-gray-700 flex-shrink-0">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 flex-shrink-0">
                                    <i data-lucide="briefcase" class="w-5 h-5"></i>
                                </div>
                            @endif
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">{{ $item->title }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate max-w-xs">{{ $item->short_description ?? 'No description provided' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                        {{ $item->category ? $item->category->name : 'Uncategorized' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex gap-2">
                            @if($item->is_published)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">Published</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20">Draft</span>
                            @endif
                            
                            @if($item->featured)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-500/10 dark:text-purple-400 border border-purple-200 dark:border-purple-500/20">Featured</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-2">
                            <button type="button" onclick="document.getElementById('viewProjectModal-{{ $item->id }}').classList.remove('hidden')" class="p-1.5 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-500/10 rounded-md transition-colors" title="View Project Details">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                            <a href="{{ route('admin.projects.edit', $item->id) }}" class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 rounded-md transition-colors" title="Edit">
                                <i data-lucide="edit-2" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.projects.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this project?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-md transition-colors" title="Delete">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 mb-4 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                <i data-lucide="folder-open" class="w-8 h-8 text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <p class="text-base font-medium text-gray-900 dark:text-white">No projects found</p>
                            <p class="mt-1 text-sm">Start by creating your first portfolio project!</p>
                            <a href="{{ route('admin.projects.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                                Add Project
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- View Project Modals -->
@foreach($items as $item)
<div id="viewProjectModal-{{ $item->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 text-left font-normal">
    <!-- Backdrop -->
    <div onclick="document.getElementById('viewProjectModal-{{ $item->id }}').classList.add('hidden')" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
    
    <!-- Modal Dialog -->
    <div class="relative z-10 w-full max-w-2xl bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700 mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                    <i data-lucide="folder-open" class="w-4 h-4"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Project Details</h3>
            </div>
            <button onclick="document.getElementById('viewProjectModal-{{ $item->id }}').classList.add('hidden')" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <div class="space-y-4">
            <!-- Cover Image & Header Info -->
            @php
                $modalCover = $item->cover_image ? format_image_url($item->cover_image) : ($item->images && $item->images->count() > 0 ? format_image_url($item->images->first()->image_path) : null);
            @endphp
            @if($modalCover)
                <div class="w-full h-48 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-700 relative">
                    <img src="{{ $modalCover }}" alt="{{ $item->title }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.parentElement.style.display='none';">
                </div>
            @endif

            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-1">
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white">{{ $item->title }}</h4>
                    <div class="flex items-center gap-2">
                        @if($item->is_published)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">Published</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20">Draft</span>
                        @endif
                        @if($item->featured)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 dark:bg-purple-500/10 dark:text-purple-400 border border-purple-200 dark:border-purple-500/20">Featured</span>
                        @endif
                    </div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">{{ $item->short_description ?: 'No short description provided.' }}</p>
            </div>

            <!-- Info Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div class="bg-gray-50 dark:bg-gray-700/30 p-3 rounded-lg border border-gray-200/60 dark:border-gray-700/60">
                    <span class="block text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium mb-1">Category</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $item->category ? $item->category->name : 'Uncategorized' }}</span>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/30 p-3 rounded-lg border border-gray-200/60 dark:border-gray-700/60">
                    <span class="block text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium mb-1">Slug</span>
                    <span class="font-mono text-xs text-gray-800 dark:text-gray-200">{{ $item->slug ?: '—' }}</span>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/30 p-3 rounded-lg border border-gray-200/60 dark:border-gray-700/60">
                    <span class="block text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium mb-1">Live Demo URL</span>
                    @if($item->live_demo_url)
                        <a href="{{ $item->live_demo_url }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline truncate block flex items-center gap-1 font-medium">
                            <span>{{ $item->live_demo_url }}</span>
                            <i data-lucide="external-link" class="w-3.5 h-3.5 flex-shrink-0"></i>
                        </a>
                    @else
                        <span class="text-gray-500 dark:text-gray-400">N/A</span>
                    @endif
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/30 p-3 rounded-lg border border-gray-200/60 dark:border-gray-700/60">
                    <span class="block text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium mb-1">GitHub Repository</span>
                    @if($item->github_url)
                        <a href="{{ $item->github_url }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline truncate block flex items-center gap-1 font-medium">
                            <span>{{ $item->github_url }}</span>
                            <i data-lucide="external-link" class="w-3.5 h-3.5 flex-shrink-0"></i>
                        </a>
                    @else
                        <span class="text-gray-500 dark:text-gray-400">N/A</span>
                    @endif
                </div>
            </div>

            <!-- Technologies Used -->
            @if($item->technologies && $item->technologies->count() > 0)
            <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-xl border border-gray-200/60 dark:border-gray-700/60">
                <span class="block text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium mb-2">Technologies Used</span>
                <div class="flex flex-wrap gap-2">
                    @foreach($item->technologies as $tech)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-500/20">
                            {{ $tech->name }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Problem Statement -->
            @if($item->problem)
            <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-xl border border-gray-200/60 dark:border-gray-700/60">
                <span class="block text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium mb-1">Problem Statement</span>
                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">{{ $item->problem }}</p>
            </div>
            @endif

            <!-- Solution -->
            @if($item->solution)
            <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-xl border border-gray-200/60 dark:border-gray-700/60">
                <span class="block text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium mb-1">Solution</span>
                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">{{ $item->solution }}</p>
            </div>
            @endif

            <!-- Architecture -->
            @if($item->architecture)
            <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-xl border border-gray-200/60 dark:border-gray-700/60">
                <span class="block text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium mb-1">Architecture</span>
                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">{{ $item->architecture }}</p>
            </div>
            @endif

            <!-- Challenges -->
            @if($item->challenges)
            <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-xl border border-gray-200/60 dark:border-gray-700/60">
                <span class="block text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium mb-1">Key Challenges</span>
                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">{{ $item->challenges }}</p>
            </div>
            @endif

            <!-- What I Learned -->
            @if($item->what_i_learned)
            <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-xl border border-gray-200/60 dark:border-gray-700/60">
                <span class="block text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium mb-1">What I Learned</span>
                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">{{ $item->what_i_learned }}</p>
            </div>
            @endif

            <!-- Additional Gallery Images -->
            @if($item->images && $item->images->count() > 0)
            <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-xl border border-gray-200/60 dark:border-gray-700/60">
                <span class="block text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium mb-2">Project Screenshots / Gallery</span>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($item->images as $img)
                        @php $gUrl = format_image_url($img->image_path); @endphp
                        <a href="{{ $gUrl }}" target="_blank" class="block h-24 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:opacity-90 transition-opacity">
                            <img src="{{ $gUrl }}" class="w-full h-full object-cover">
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
            <button onclick="document.getElementById('viewProjectModal-{{ $item->id }}').classList.add('hidden')" type="button" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                Close
            </button>
            <a href="{{ route('admin.projects.edit', $item->id) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-md shadow-indigo-500/20 inline-flex items-center">
                <i data-lucide="edit-2" class="w-4 h-4 mr-1.5"></i> Edit Project
            </a>
        </div>
    </div>
</div>
@endforeach
@endsection
