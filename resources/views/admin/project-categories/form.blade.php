@extends('layouts.admin')

@section('header', isset($item) ? 'Edit Project Category' : 'Add Project Category')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ isset($item) ? 'Edit Project Category' : 'Add Project Category' }}</h1>
        <a href="{{ route('admin.project-categories.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Back to Categories</a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6">
        <form action="{{ isset($item) ? route('admin.project-categories.update', $item) : route('admin.project-categories.store') }}" method="POST" class="space-y-5">
            @csrf
            @if(isset($item)) @method('PUT') @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name', $item->name ?? '') }}" required class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Slug (optional)</label>
                <input type="text" name="slug" value="{{ old('slug', $item->slug ?? '') }}" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort Order</label>
                <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $item->sort_order ?? 0) }}" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->is_active ?? true)) class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                Active
            </label>

            <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md shadow-indigo-500/20">
                {{ isset($item) ? 'Update Category' : 'Save Category' }}
            </button>
        </form>
    </div>
</div>
@endsection