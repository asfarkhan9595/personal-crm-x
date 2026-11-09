@extends('layouts.admin')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ isset($item) ? 'Edit' : 'Create' }}</h1>
    </div>
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <form action="{{ isset($item) ? route('admin.experience.update', $item->id) : route('admin.experience.store') }}" method="POST">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            <p class="text-sm text-gray-500 mb-4">Edit fields dynamically (placeholder for comprehensive forms).</p>
            <!-- Form fields go here based on model -->
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Save</button>
        </form>
    </div>
</div>
@endsection