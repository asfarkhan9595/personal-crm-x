@extends('layouts.admin')
@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Top Header -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Skill Categories</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Organize and manage skill categories across your portfolio.</p>
        </div>
        <button onclick="document.getElementById('addSkillCatModal').classList.remove('hidden')" type="button" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-lg shadow-indigo-500/20">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add Skill Category
        </button>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/20 flex items-start gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5"></i>
            <div>
                <span class="font-semibold block mb-0.5 text-red-700 dark:text-red-300">Deletion Blocked</span>
                <span class="text-sm text-red-600 dark:text-red-400">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Search & Filter Controls -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 mb-6 shadow-sm">
        <form method="GET" action="{{ route('admin.skill-categories.index') }}" class="flex flex-col sm:flex-row gap-4 items-center justify-between">
            <!-- Search Input -->
            <div class="relative w-full sm:w-80">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search skill categories..." class="w-full pl-9 pr-3.5 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Sort, Status, Per Page & Actions -->
            <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto justify-end">
                <!-- Status Filter -->
                <div>
                    <select name="status" onchange="this.form.submit()" class="text-xs rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active Only</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                    </select>
                </div>

                <!-- Per Page Selector -->
                <div>
                    <select name="per_page" onchange="this.form.submit()" class="text-xs rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>Show 5 / page</option>
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>Show 10 / page</option>
                        <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>Show 15 / page</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>Show 25 / page</option>
                    </select>
                </div>

                <!-- Sort Dropdown -->
                <div class="flex items-center gap-2">
                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400 whitespace-nowrap">Sort:</label>
                    <select name="sort" onchange="this.form.submit()" class="text-xs rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="sort_order_asc" {{ request('sort', 'sort_order_asc') == 'sort_order_asc' ? 'selected' : '' }}>Sort Order (Low to High)</option>
                        <option value="sort_order_desc" {{ request('sort') == 'sort_order_desc' ? 'selected' : '' }}>Sort Order (High to Low)</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                        <option value="skills_desc" {{ request('sort') == 'skills_desc' ? 'selected' : '' }}>Most Skills</option>
                    </select>
                </div>

                @if(request('search') || request('status') || request('sort') || request('per_page'))
                    <a href="{{ route('admin.skill-categories.index') }}" class="p-2 text-xs font-medium text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white bg-gray-100 dark:bg-gray-700 rounded-lg transition-colors whitespace-nowrap" title="Clear Filters">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800/80 backdrop-blur-sm shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Linked Skills</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($items as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            <div class="flex items-center gap-2">
                                <span>{{ $item->name }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                    #{{ $item->sort_order }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs font-mono text-gray-500 dark:text-gray-400">
                            {{ $item->slug }}
                        </td>
                        <td class="px-6 py-4 text-sm relative">
                            @if($item->skills_count > 0)
                                <button type="button" onclick="toggleSkillCatPopover({{ $item->id }})" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-500/10 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/20 hover:bg-indigo-200 dark:hover:bg-indigo-500/20 transition-colors">
                                    <i data-lucide="code" class="w-3.5 h-3.5"></i>
                                    {{ $item->skills_count }} {{ Str::plural('skill', $item->skills_count) }}
                                </button>

                                <!-- Linked Skills Popover -->
                                <div id="skill-cat-popover-{{ $item->id }}" class="skill-cat-popover hidden absolute left-6 top-12 z-20 w-64 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 p-3">
                                    <div class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2 border-b border-gray-100 dark:border-gray-700 pb-1 flex justify-between items-center">
                                        <span>Linked Skills ({{ $item->skills_count }})</span>
                                        <button type="button" onclick="toggleSkillCatPopover({{ $item->id }})" class="text-gray-400 hover:text-gray-600">&times;</button>
                                    </div>
                                    <ul class="space-y-1.5 max-h-40 overflow-y-auto">
                                        @foreach($item->skills as $s)
                                            <li>
                                                <a href="{{ route('admin.skills.index', ['search' => $s->name]) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline block truncate">
                                                    &bull; {{ $s->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                    0 skills
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('admin.skill-categories.toggle-active', $item->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <button type="submit" title="Click to toggle status (Active / Inactive)">
                                    @if($item->is_active)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 cursor-pointer hover:bg-emerald-200 transition-colors">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 border border-gray-200 dark:border-gray-600 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                            Inactive
                                        </span>
                                    @endif
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium space-x-1">
                            <button type="button" onclick="document.getElementById('viewSkillCatModal-{{ $item->id }}').classList.remove('hidden')" class="p-1.5 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-500/10 rounded-md transition-colors" title="View Skill Category Details">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                            <button type="button" onclick="document.getElementById('editSkillCatModal-{{ $item->id }}').classList.remove('hidden')" class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 rounded-md transition-colors" title="Edit">
                                <i data-lucide="edit-2" class="w-4 h-4"></i>
                            </button>
                            <form action="{{ route('admin.skill-categories.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this skill category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-md transition-colors" title="Delete">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <i data-lucide="folder" class="w-10 h-10 text-gray-400 mb-2"></i>
                                <p class="text-base font-medium">No skill categories found</p>
                                <p class="text-xs mt-1">Try adjusting your search or add a new skill category.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="text-xs text-gray-500 dark:text-gray-400">
                Showing <span class="font-semibold text-gray-900 dark:text-white">{{ $items->firstItem() ?? 0 }}</span> to <span class="font-semibold text-gray-900 dark:text-white">{{ $items->lastItem() ?? 0 }}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{ $items->total() }}</span> categories
            </div>
            <div>
                {{ $items->links() }}
            </div>
        </div>
    </div>

    <!-- Add Skill Category Modal -->
    <div id="addSkillCatModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div onclick="document.getElementById('addSkillCatModal').classList.add('hidden')" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        
        <!-- Modal Dialog -->
        <div class="relative z-10 w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Add New Skill Category</h3>
                <button onclick="document.getElementById('addSkillCatModal').classList.add('hidden')" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('admin.skill-categories.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Frontend, Backend, DevOps" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="0" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div class="mb-6 flex items-center">
                    <input type="checkbox" name="is_active" id="add_cat_is_active" value="1" checked class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="add_cat_is_active" class="ml-2 block text-xs font-medium text-gray-700 dark:text-gray-300">Set Status to Active</label>
                </div>

                <div class="flex justify-end gap-3">
                    <button onclick="document.getElementById('addSkillCatModal').classList.add('hidden')" type="button" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-md shadow-indigo-500/20">
                        Save Skill Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Skill Category Modals -->
    @foreach($items as $item)
    <div id="viewSkillCatModal-{{ $item->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 text-left font-normal">
        <!-- Backdrop -->
        <div onclick="document.getElementById('viewSkillCatModal-{{ $item->id }}').classList.add('hidden')" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        
        <!-- Modal Dialog -->
        <div class="relative z-10 w-full max-w-lg bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700 mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <i data-lucide="folder" class="w-4 h-4"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Skill Category Details</h3>
                </div>
                <button onclick="document.getElementById('viewSkillCatModal-{{ $item->id }}').classList.add('hidden')" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="space-y-4">
                <!-- Title Header -->
                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white">{{ $item->name }}</h4>
                    <p class="text-xs font-mono text-gray-500 dark:text-gray-400 mt-1">Slug: {{ $item->slug }}</p>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="bg-gray-50 dark:bg-gray-700/30 p-3 rounded-lg border border-gray-200/60 dark:border-gray-700/60">
                        <span class="block text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium mb-1">Status</span>
                        @if($item->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-400">Active</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">Inactive</span>
                        @endif
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/30 p-3 rounded-lg border border-gray-200/60 dark:border-gray-700/60">
                        <span class="block text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium mb-1">Sort Order</span>
                        <span class="font-mono text-gray-900 dark:text-white">#{{ $item->sort_order }}</span>
                    </div>
                </div>

                <!-- Linked Skills Block -->
                <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-xl border border-gray-200/60 dark:border-gray-700/60">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Linked Skills</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-500/10 dark:text-indigo-400">
                            {{ $item->skills_count }} {{ Str::plural('skill', $item->skills_count) }}
                        </span>
                    </div>
                    @if($item->skills && $item->skills->count() > 0)
                        <ul class="space-y-2 mt-2">
                            @foreach($item->skills as $s)
                                <li class="flex items-center justify-between p-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $s->name }}</span>
                                    <a href="{{ route('admin.skills.index', ['search' => $s->name]) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1 font-medium flex-shrink-0">
                                        <span>View</span>
                                        <i data-lucide="external-link" class="w-3 h-3"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-xs text-gray-500 dark:text-gray-400 italic">No skills linked to this category yet.</p>
                    @endif
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button onclick="document.getElementById('viewSkillCatModal-{{ $item->id }}').classList.add('hidden')" type="button" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                    Close
                </button>
                <button onclick="document.getElementById('viewSkillCatModal-{{ $item->id }}').classList.add('hidden'); document.getElementById('editSkillCatModal-{{ $item->id }}').classList.remove('hidden');" type="button" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-md shadow-indigo-500/20 inline-flex items-center">
                    <i data-lucide="edit-2" class="w-4 h-4 mr-1.5"></i> Edit Category
                </button>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Edit Skill Category Modals -->
    @foreach($items as $item)
    <div id="editSkillCatModal-{{ $item->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 text-left font-normal">
        <!-- Backdrop -->
        <div onclick="document.getElementById('editSkillCatModal-{{ $item->id }}').classList.add('hidden')" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        
        <!-- Modal Dialog -->
        <div class="relative z-10 w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Edit Skill Category</h3>
                <button onclick="document.getElementById('editSkillCatModal-{{ $item->id }}').classList.add('hidden')" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('admin.skill-categories.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category Name *</label>
                    <input type="text" name="name" value="{{ old('name', $item->name) }}" required class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $item->sort_order) }}" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="mb-6 flex items-center">
                    <input type="checkbox" name="is_active" id="edit_cat_active_{{ $item->id }}" value="1" {{ $item->is_active ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="edit_cat_active_{{ $item->id }}" class="ml-2 block text-xs font-medium text-gray-700 dark:text-gray-300">Set Status to Active</label>
                </div>
                <div class="flex justify-end gap-3">
                    <button onclick="document.getElementById('editSkillCatModal-{{ $item->id }}').classList.add('hidden')" type="button" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-md shadow-indigo-500/20">
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endforeach
</div>

<script>
function toggleSkillCatPopover(id) {
    const target = document.getElementById('skill-cat-popover-' + id);
    const isHidden = target.classList.contains('hidden');
    
    // Close all popovers
    document.querySelectorAll('.skill-cat-popover').forEach(el => el.classList.add('hidden'));
    
    // Toggle target
    if (isHidden) {
        target.classList.remove('hidden');
    }
}
</script>
@endsection