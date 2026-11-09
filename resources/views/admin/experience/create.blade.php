@extends('layouts.admin')
@section('header', 'Create Experience')
@section('content')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
    <form action="{{ route('admin.experience.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium">title</label>
                <input type="text" name="title" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">company</label>
                <input type="text" name="company" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">location</label>
                <input type="text" name="location" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">start_date</label>
                <input type="text" name="start_date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">end_date</label>
                <input type="text" name="end_date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">description</label>
                <input type="text" name="description" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">sort_order</label>
                <input type="text" name="sort_order" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="flex items-center text-sm font-medium">
                    <input type="checkbox" name="is_current" value="1" class="rounded border-gray-300 dark:border-gray-700">
                    <span class="ml-2">is_current</span>
                </label>
            </div>
            <div>
                <label class="flex items-center text-sm font-medium">
                    <input type="checkbox" name="is_published" value="1" class="rounded border-gray-300 dark:border-gray-700">
                    <span class="ml-2">is_published</span>
                </label>
            </div>

            <div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Save</button>
            </div>
        </div>
    </form>
</div>
@endsection
