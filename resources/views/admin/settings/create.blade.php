@extends('layouts.admin')
@section('header', 'Create Setting')
@section('content')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
    <form action="{{ route('admin.settings.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium">key</label>
                <input type="text" name="key" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">value</label>
                <input type="text" name="value" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">type</label>
                <input type="text" name="type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>

            <div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Save</button>
            </div>
        </div>
    </form>
</div>
@endsection
