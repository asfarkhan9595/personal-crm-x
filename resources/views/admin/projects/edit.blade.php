@extends('layouts.admin')
@section('header', 'Edit Project')
@section('content')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
    <form action="{{ route('admin.projects.update', $project) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium">category_id</label>
                <input type="text" name="category_id" value="{{ $project->category_id }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">title</label>
                <input type="text" name="title" value="{{ $project->title }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">slug</label>
                <input type="text" name="slug" value="{{ $project->slug }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">short_description</label>
                <input type="text" name="short_description" value="{{ $project->short_description }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">problem</label>
                <input type="text" name="problem" value="{{ $project->problem }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">solution</label>
                <input type="text" name="solution" value="{{ $project->solution }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">architecture</label>
                <input type="text" name="architecture" value="{{ $project->architecture }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">challenges</label>
                <input type="text" name="challenges" value="{{ $project->challenges }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">what_i_learned</label>
                <input type="text" name="what_i_learned" value="{{ $project->what_i_learned }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">live_demo_url</label>
                <input type="text" name="live_demo_url" value="{{ $project->live_demo_url }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">github_url</label>
                <input type="text" name="github_url" value="{{ $project->github_url }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">cover_image</label>
                <input type="text" name="cover_image" value="{{ $project->cover_image }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>
            <div>
                <label class="flex items-center text-sm font-medium">
                    <input type="checkbox" name="featured" value="1" {{ $project->featured ? 'checked' : '' }} class="rounded border-gray-300 dark:border-gray-700">
                    <span class="ml-2">featured</span>
                </label>
            </div>
            <div>
                <label class="flex items-center text-sm font-medium">
                    <input type="checkbox" name="is_published" value="1" {{ $project->is_published ? 'checked' : '' }} class="rounded border-gray-300 dark:border-gray-700">
                    <span class="ml-2">is_published</span>
                </label>
            </div>
            <div>
                <label class="block text-sm font-medium">sort_order</label>
                <input type="text" name="sort_order" value="{{ $project->sort_order }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
            </div>

            <div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Update</button>
            </div>
        </div>
    </form>
</div>
@endsection
