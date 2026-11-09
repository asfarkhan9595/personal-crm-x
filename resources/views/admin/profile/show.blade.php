@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Top Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                <i data-lucide="user" class="w-7 h-7 text-indigo-600 dark:text-indigo-400"></i>
                Profile Overview
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">View your public portfolio profile information.</p>
        </div>
        <a href="{{ route('admin.profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-lg shadow-indigo-500/20">
            <i data-lucide="edit-3" class="w-4 h-4 mr-2"></i> Edit Profile
        </a>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Profile Details Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
        <!-- Hero Header Cover -->
        <div class="h-32 bg-gradient-to-r from-indigo-500 via-purple-500 to-emerald-500 opacity-90"></div>

        <div class="px-6 sm:px-8 pb-8 relative">
            <!-- Profile Avatar & Title Row -->
            <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4 -mt-16 mb-6">
                <div class="flex items-end gap-4">
                    <img 
                        src="{{ format_image_url($item->profile_image, 'https://ui-avatars.com/api/?name=' . urlencode($item->name ?: 'Admin') . '&background=6366f1&color=fff&size=128') }}" 
                        alt="{{ $item->name ?: 'Profile Image' }}"
                        class="w-28 h-28 rounded-2xl border-4 border-white dark:border-gray-800 object-cover shadow-md bg-white dark:bg-gray-700"
                    >
                    <div class="mb-1">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $item->name ?: 'Asfar Khan' }}</h2>
                        <p class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 flex items-center gap-1.5 mt-0.5">
                            <i data-lucide="briefcase" class="w-4 h-4"></i>
                            {{ $item->primary_role ?: 'Developer' }}
                        </p>
                    </div>
                </div>

                <a href="{{ route('admin.profile.edit') }}" class="px-4 py-2 text-xs font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg transition-colors inline-flex items-center gap-1.5">
                    <i data-lucide="edit-2" class="w-3.5 h-3.5"></i> Edit Profile Information
                </a>
            </div>

            <!-- Secondary Roles -->
            @if(!empty($item->secondary_roles))
            <div class="mb-6">
                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Secondary Roles</span>
                <div class="flex flex-wrap gap-2">
                    @foreach((array)$item->secondary_roles as $role)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400 border border-indigo-200/60 dark:border-indigo-500/20">
                            {{ $role }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Hero Supporting Text / Tagline -->
            @if($item->hero_supporting_text)
            <div class="mb-6 p-4 rounded-xl bg-gray-50 dark:bg-gray-700/40 border border-gray-200/80 dark:border-gray-700/80">
                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                    <i data-lucide="quote" class="w-3.5 h-3.5 text-indigo-500"></i> Hero Tagline
                </span>
                <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed font-medium">
                    "{{ $item->hero_supporting_text }}"
                </p>
            </div>
            @endif

            <!-- About Me Section Details -->
            @if($item->about || $item->about_title || $item->years_experience || !empty($item->about_highlights))
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider flex items-center gap-1.5">
                        <i data-lucide="file-text" class="w-4 h-4 text-emerald-500"></i> About Section Information
                    </span>
                    <div class="flex items-center gap-2">
                        @if($item->years_experience)
                            <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800">
                                {{ $item->years_experience }} Experience
                            </span>
                        @endif
                        @if($item->projects_count)
                            <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-indigo-100 text-indigo-800 dark:bg-indigo-950 dark:text-indigo-300 border border-indigo-300 dark:border-indigo-800">
                                {{ $item->projects_count }} Projects
                            </span>
                        @endif
                    </div>
                </div>

                @if($item->about_title)
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-2">{{ $item->about_title }}</h3>
                @endif

                @if($item->about)
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-700/30 border border-gray-200/60 dark:border-gray-700/60 text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap mb-4">
                        {{ $item->about }}
                    </div>
                @endif

                @if(!empty($item->about_highlights))
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2">Key Highlights / Bullets:</span>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array)$item->about_highlights as $highlight)
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-500/20">
                                    <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-500"></i>
                                    {{ $highlight }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

