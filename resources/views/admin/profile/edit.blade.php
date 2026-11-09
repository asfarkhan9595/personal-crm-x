@extends('layouts.admin')

@section('header', 'Edit Profile')

@section('content')
<div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6">
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif
    <form action="{{ route('admin.profile.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name', $item->name ?? '') }}" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Primary Role</label>
                <input type="text" name="primary_role" value="{{ old('primary_role', $item->primary_role ?? '') }}" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Secondary Roles (comma separated)</label>
                <input type="text" name="secondary_roles" value="{{ old('secondary_roles', $item->secondary_roles_string ?? '') }}" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hero Text</label>
                <textarea name="hero_supporting_text" rows="3" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white">{{ old('hero_supporting_text', $item->hero_supporting_text ?? '') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">About</label>
                <textarea name="about" rows="4" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white">{{ old('about', $item->about ?? '') }}</textarea>
            </div>
            <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg shadow-indigo-500/30 transition-all">Save Profile</button>
            </div>
        </div>
    </form>
</div>
@endsection