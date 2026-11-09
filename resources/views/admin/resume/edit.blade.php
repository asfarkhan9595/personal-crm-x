@extends('layouts.admin')
@section('header', 'Edit Resume')
@section('content')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
    <form action="{{ route('admin.resume.update', $resume) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium">title</label>
                <input type="text" name="title" value="{{ $resume->title }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">file_path</label>
                <input type="text" name="file_path" value="{{ $resume->file_path }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="flex items-center text-sm font-medium">
                    <input type="checkbox" name="is_active" value="1" {{ $resume->is_active ? 'checked' : '' }} class="rounded border-gray-300 dark:border-gray-700">
                    <span class="ml-2">is_active</span>
                </label>
            </div>

            <div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Update</button>
            </div>
        </div>
    </form>
</div>
@endsection
