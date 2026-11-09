@extends('layouts.admin')
@section('header', 'Create SocialLink')
@section('content')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
    <form action="{{ route('admin.social-links.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium">platform</label>
                <input type="text" name="platform" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">url</label>
                <input type="text" name="url" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">icon</label>
                <input type="text" name="icon" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">sort_order</label>
                <input type="text" name="sort_order" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="flex items-center text-sm font-medium">
                    <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 dark:border-gray-700">
                    <span class="ml-2">is_active</span>
                </label>
            </div>

            <div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Save</button>
            </div>
        </div>
    </form>
</div>
@endsection
