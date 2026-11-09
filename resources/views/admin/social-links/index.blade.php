@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Top Header -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Social Links</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your social profiles and public links (GitHub, LinkedIn, Twitter, YouTube, etc.).</p>
        </div>
        <button onclick="document.getElementById('addSocialModal').classList.remove('hidden')" type="button" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-lg shadow-indigo-500/20">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add Social Link
        </button>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/20">
            <div class="flex items-center gap-3 mb-1">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                <span class="font-semibold">Please fix the following errors:</span>
            </div>
            <ul class="list-disc list-inside text-xs pl-8 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Search & Filter Controls -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 mb-6 shadow-sm">
        <form method="GET" action="{{ route('admin.social-links.index') }}" class="flex flex-col sm:flex-row gap-4 items-center justify-between">
            <!-- Search Input -->
            <div class="relative w-full sm:w-80">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search platform, URL, icon..." class="w-full pl-9 pr-3.5 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Filters, Per Page & Sort Controls -->
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
                        <option value="platform_asc" {{ request('sort') == 'platform_asc' ? 'selected' : '' }}>Platform (A-Z)</option>
                        <option value="platform_desc" {{ request('sort') == 'platform_desc' ? 'selected' : '' }}>Platform (Z-A)</option>
                    </select>
                </div>

                @if(request('search') || request('status') || request('sort') || request('per_page'))
                    <a href="{{ route('admin.social-links.index') }}" class="p-2 text-xs font-medium text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white bg-gray-100 dark:bg-gray-700 rounded-lg transition-colors whitespace-nowrap" title="Clear Filters">
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
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Platform</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">URL</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sort Order</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($items as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400 flex-shrink-0">
                                    <i data-lucide="{{ $item->icon ?: 'link' }}" class="w-4 h-4"></i>
                                </div>
                                <span class="font-semibold">{{ $item->platform }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs font-mono text-gray-500 dark:text-gray-400 max-w-xs truncate">
                            <a href="{{ $item->url }}" target="{{ str_starts_with($item->url, 'mailto:') ? '_self' : '_blank' }}" class="text-indigo-600 dark:text-indigo-400 hover:underline inline-flex items-center gap-1">
                                <span class="truncate">{{ $item->url }}</span>
                                <i data-lucide="external-link" class="w-3 h-3 flex-shrink-0"></i>
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('admin.social-links.toggle-active', $item->id) }}" method="POST" class="inline-block">
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
                        <td class="px-6 py-4 text-xs font-mono text-gray-500 dark:text-gray-400">
                            #{{ $item->sort_order }}
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium space-x-1">
                            <button type="button" onclick="document.getElementById('viewSocialModal-{{ $item->id }}').classList.remove('hidden')" class="p-1.5 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-md transition-colors" title="View Social Link Details">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                            <button type="button" onclick="document.getElementById('editSocialModal-{{ $item->id }}').classList.remove('hidden')" class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-md transition-colors" title="Edit Social Link">
                                <i data-lucide="edit-2" class="w-4 h-4"></i>
                            </button>
                            <button type="button" onclick="document.getElementById('deleteSocialModal-{{ $item->id }}').classList.remove('hidden')" class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-md transition-colors" title="Delete">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <i data-lucide="share-2" class="w-10 h-10 text-gray-400 mb-2"></i>
                                <p class="text-base font-medium">No social links found</p>
                                <p class="text-xs mt-1">Try adjusting your filters or add a new social link.</p>
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
                Showing <span class="font-semibold text-gray-900 dark:text-white">{{ $items->firstItem() ?? 0 }}</span> to <span class="font-semibold text-gray-900 dark:text-white">{{ $items->lastItem() ?? 0 }}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{ $items->total() }}</span> social links
            </div>
            <div>
                {{ $items->links() }}
            </div>
        </div>
    </div>

    <!-- Add Social Link Modal -->
    <div id="addSocialModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div onclick="document.getElementById('addSocialModal').classList.add('hidden')" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        
        <!-- Modal Dialog -->
        <div class="relative z-10 w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Add New Social Link</h3>
                <button onclick="document.getElementById('addSocialModal').classList.add('hidden')" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('admin.social-links.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Platform *</label>
                    <select name="platform_select" id="add_platform_select" onchange="if(this.value !== 'Other') { document.getElementById('add_platform_input').value = this.value; } else { document.getElementById('add_platform_input').value = ''; }" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white mb-2">
                        <option value="GitHub">GitHub</option>
                        <option value="LinkedIn">LinkedIn</option>
                        <option value="X / Twitter">X / Twitter</option>
                        <option value="YouTube">YouTube</option>
                        <option value="WhatsApp">WhatsApp</option>
                        <option value="Email">Email</option>
                        <option value="Website">Website</option>
                        <option value="Other">Other / Custom</option>
                    </select>
                    <input type="text" name="platform" id="add_platform_input" value="GitHub" required placeholder="Platform name" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">URL / Email / WhatsApp *</label>
                    <input type="text" name="url" required placeholder="https://github.com/username, mailto:user@example.com, or https://wa.me/1234567890" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">For WhatsApp, enter wa.me link or phone number.</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Icon Identifier (optional)</label>
                    <input type="text" name="icon" placeholder="github, linkedin, twitter, youtube, whatsapp, mail, globe, link" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Leave empty to auto-detect icon from platform.</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="0" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div class="mb-6 flex items-center">
                    <input type="checkbox" name="is_active" id="add_social_is_active" value="1" checked class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="add_social_is_active" class="ml-2 block text-xs font-medium text-gray-700 dark:text-gray-300">Set Status to Active</label>
                </div>

                <div class="flex justify-end gap-3">
                    <button onclick="document.getElementById('addSocialModal').classList.add('hidden')" type="button" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-md shadow-indigo-500/20">
                        Save Social Link
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Social Link Modals -->
    @foreach($items as $item)
    <div id="viewSocialModal-{{ $item->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 text-left font-normal">
        <!-- Backdrop -->
        <div onclick="document.getElementById('viewSocialModal-{{ $item->id }}').classList.add('hidden')" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        
        <!-- Modal Dialog -->
        <div class="relative z-10 w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700 mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <i data-lucide="{{ $item->icon ?: 'link' }}" class="w-4 h-4"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Social Link Details</h3>
                </div>
                <button onclick="document.getElementById('viewSocialModal-{{ $item->id }}').classList.add('hidden')" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="space-y-4">
                <!-- Title Header -->
                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl border border-gray-200 dark:border-gray-700 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 flex-shrink-0">
                        <i data-lucide="{{ $item->icon ?: 'link' }}" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white">{{ $item->platform }}</h4>
                        <p class="text-xs font-mono text-gray-500 dark:text-gray-400 mt-0.5">Icon: {{ $item->icon ?: 'link (auto)' }}</p>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 gap-4 text-sm">
                    <div class="bg-gray-50 dark:bg-gray-700/30 p-3 rounded-lg border border-gray-200/60 dark:border-gray-700/60">
                        <span class="block text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium mb-1">Profile / Link URL</span>
                        <a href="{{ $item->url }}" target="{{ str_starts_with($item->url, 'mailto:') ? '_self' : '_blank' }}" class="text-indigo-600 dark:text-indigo-400 hover:underline inline-flex items-center gap-1 font-medium text-xs break-all">
                            <span>{{ $item->url }}</span>
                            <i data-lucide="external-link" class="w-3.5 h-3.5 flex-shrink-0"></i>
                        </a>
                    </div>
                </div>

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
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button onclick="document.getElementById('viewSocialModal-{{ $item->id }}').classList.add('hidden')" type="button" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                    Close
                </button>
                <button onclick="document.getElementById('viewSocialModal-{{ $item->id }}').classList.add('hidden'); document.getElementById('editSocialModal-{{ $item->id }}').classList.remove('hidden');" type="button" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-md shadow-indigo-500/20 inline-flex items-center">
                    <i data-lucide="edit-2" class="w-4 h-4 mr-1.5"></i> Edit Social Link
                </button>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Edit Social Link Modals -->
    @foreach($items as $item)
    <div id="editSocialModal-{{ $item->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 text-left font-normal">
        <!-- Backdrop -->
        <div onclick="document.getElementById('editSocialModal-{{ $item->id }}').classList.add('hidden')" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        
        <!-- Modal Dialog -->
        <div class="relative z-10 w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Edit Social Link</h3>
                <button onclick="document.getElementById('editSocialModal-{{ $item->id }}').classList.add('hidden')" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('admin.social-links.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Platform *</label>
                    <select onchange="if(this.value !== 'Other') { document.getElementById('edit_platform_input_{{ $item->id }}').value = this.value; }" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white mb-2">
                        <option value="GitHub" {{ $item->platform == 'GitHub' ? 'selected' : '' }}>GitHub</option>
                        <option value="LinkedIn" {{ $item->platform == 'LinkedIn' ? 'selected' : '' }}>LinkedIn</option>
                        <option value="X / Twitter" {{ in_array($item->platform, ['X / Twitter', 'Twitter', 'X']) ? 'selected' : '' }}>X / Twitter</option>
                        <option value="YouTube" {{ $item->platform == 'YouTube' ? 'selected' : '' }}>YouTube</option>
                        <option value="WhatsApp" {{ in_array($item->platform, ['WhatsApp', 'whatsapp', 'WA']) ? 'selected' : '' }}>WhatsApp</option>
                        <option value="Email" {{ $item->platform == 'Email' ? 'selected' : '' }}>Email</option>
                        <option value="Website" {{ $item->platform == 'Website' ? 'selected' : '' }}>Website</option>
                        <option value="Other" {{ !in_array($item->platform, ['GitHub', 'LinkedIn', 'X / Twitter', 'Twitter', 'X', 'YouTube', 'WhatsApp', 'Email', 'Website']) ? 'selected' : '' }}>Other / Custom</option>
                    </select>
                    <input type="text" name="platform" id="edit_platform_input_{{ $item->id }}" value="{{ $item->platform }}" required class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">URL / Email / WhatsApp *</label>
                    <input type="text" name="url" value="{{ $item->url }}" required class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Icon Identifier (optional)</label>
                    <input type="text" name="icon" value="{{ $item->icon }}" placeholder="github, linkedin, twitter, youtube, whatsapp, mail, globe, link" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ $item->sort_order }}" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div class="mb-6 flex items-center">
                    <input type="checkbox" name="is_active" id="edit_social_active_{{ $item->id }}" value="1" {{ $item->is_active ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="edit_social_active_{{ $item->id }}" class="ml-2 block text-xs font-medium text-gray-700 dark:text-gray-300">Set Status to Active</label>
                </div>

                <div class="flex justify-end gap-3">
                    <button onclick="document.getElementById('editSocialModal-{{ $item->id }}').classList.add('hidden')" type="button" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-md shadow-indigo-500/20">
                        Update Social Link
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Social Link Confirmation Modal -->
    <div id="deleteSocialModal-{{ $item->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 text-left font-normal">
        <!-- Backdrop -->
        <div onclick="document.getElementById('deleteSocialModal-{{ $item->id }}').classList.add('hidden')" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        
        <!-- Modal Dialog -->
        <div class="relative z-10 w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center text-red-600 dark:text-red-400 flex-shrink-0">
                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Delete Social Link</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Are you sure you want to delete this social link?</p>
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
                <p class="font-bold text-sm text-gray-900 dark:text-white">{{ $item->platform }}</p>
                <p class="text-xs text-indigo-600 dark:text-indigo-400 truncate">{{ $item->url }}</p>
            </div>

            <div class="flex justify-end gap-3">
                <button onclick="document.getElementById('deleteSocialModal-{{ $item->id }}').classList.add('hidden')" type="button" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                    Cancel
                </button>
                <form action="{{ route('admin.social-links.destroy', $item->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow-md shadow-red-500/20">
                        Delete Link
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection