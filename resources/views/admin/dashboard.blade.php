@extends('layouts.admin')

@section('header', 'Dashboard Overview')

@section('content')
@php
    $totalProjects = \App\Models\Project::count();
    $featuredProjects = \App\Models\Project::where('featured', true)->count();
    $totalSkills = \App\Models\Skill::count();
    $unreadMessages = \App\Models\ContactMessage::where('status', 'new')->orWhere('is_read', false)->count();
    $totalMessages = \App\Models\ContactMessage::count();
    $recentMessages = \App\Models\ContactMessage::latest()->take(4)->get();
    $recentProjects = \App\Models\Project::with(['category', 'images'])->latest()->take(4)->get();
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex items-center gap-4">
        <div class="p-3 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-lg">
            <i data-lucide="briefcase" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Projects</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalProjects }}</h3>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex items-center gap-4">
        <div class="p-3 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-lg">
            <i data-lucide="mail" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Contact Messages</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                {{ $totalMessages }}
                @if($unreadMessages > 0)
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-emerald-500 text-white animate-pulse">
                        {{ $unreadMessages }} New
                    </span>
                @endif
            </h3>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex items-center gap-4">
        <div class="p-3 bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-lg">
            <i data-lucide="code" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Skills</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalSkills }}</h3>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex items-center gap-4">
        <div class="p-3 bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 rounded-lg">
            <i data-lucide="award" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Featured Projects</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $featuredProjects }}</h3>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Recent Projects Widget -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i data-lucide="briefcase" class="w-5 h-5 text-indigo-600 dark:text-indigo-400"></i>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Recent Projects</h3>
            </div>
            <a href="{{ route('admin.projects.index') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">View All Projects</a>
        </div>
        <div class="p-6">
            @if($recentProjects->count() > 0)
                <div class="space-y-4">
                    @foreach($recentProjects as $project)
                        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-3 last:border-0 last:pb-0">
                            <div class="flex items-center gap-3.5 min-w-0">
                                @php
                                    $dashThumb = $project->cover_image ? format_image_url($project->cover_image) : ($project->images && $project->images->count() > 0 ? format_image_url($project->images->first()->image_path) : null);
                                @endphp
                                @if($dashThumb)
                                    <img src="{{ $dashThumb }}" alt="{{ $project->title }}" class="w-10 h-10 rounded-lg object-cover border border-gray-200 dark:border-gray-700 flex-shrink-0">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="folder" class="w-5 h-5"></i>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $project->title }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $project->category ? $project->category->name : 'Uncategorized' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                @if($project->is_published)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-400">
                                        Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-400">
                                        Draft
                                    </span>
                                @endif
                                <a href="{{ route('admin.projects.edit', $project->id) }}" class="p-1 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500 dark:text-gray-400 text-sm">
                    <i data-lucide="briefcase" class="w-8 h-8 mx-auto text-gray-400 mb-2"></i>
                    No projects found.
                </div>
            @endif
        </div>
    </div>

    <!-- Contact Messages Widget -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i data-lucide="mail" class="w-5 h-5 text-indigo-600 dark:text-indigo-400"></i>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Contact Messages</h3>
            </div>
            <a href="{{ route('admin.contact-messages.index') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">View All Messages</a>
        </div>
        <div class="p-6">
            @if($recentMessages->count() > 0)
                <div class="space-y-4">
                    @foreach($recentMessages as $msg)
                        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-3 last:border-0 last:pb-0">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-full bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                    {{ strtoupper(substr($msg->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $msg->name }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $msg->subject ?: 'No Subject' }} — {{ $msg->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                @if($msg->status === 'new' || !$msg->is_read)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-400">
                                        New
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                        {{ ucfirst($msg->status) }}
                                    </span>
                                @endif
                                <a href="{{ route('admin.contact-messages.index') }}" class="p-1 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">
                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500 dark:text-gray-400 text-sm">
                    <i data-lucide="inbox" class="w-8 h-8 mx-auto text-gray-400 mb-2"></i>
                    No contact messages received yet.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Quick Actions</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.projects.index') }}" class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors">
                <i data-lucide="plus-circle" class="w-6 h-6 text-indigo-600 dark:text-indigo-400"></i>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Add Project</span>
            </a>
            <a href="{{ route('admin.profile.edit') }}" class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors">
                <i data-lucide="edit-3" class="w-6 h-6 text-indigo-600 dark:text-indigo-400"></i>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Update Profile</span>
            </a>
            <a href="{{ route('admin.contact-messages.index') }}" class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors">
                <i data-lucide="mail" class="w-6 h-6 text-indigo-600 dark:text-indigo-400"></i>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Contact Inbox</span>
            </a>
            <a href="{{ route('admin.contact-settings.index') }}" class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors">
                <i data-lucide="sliders" class="w-6 h-6 text-indigo-600 dark:text-indigo-400"></i>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Contact Settings</span>
            </a>
        </div>
    </div>
</div>
@endsection
