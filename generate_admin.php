<?php

$resources = [
    ['model' => 'Project', 'route' => 'projects', 'var' => 'project'],
    ['model' => 'Skill', 'route' => 'skills', 'var' => 'skill'],
    ['model' => 'Experience', 'route' => 'experience', 'var' => 'experience'],
    ['model' => 'SocialLink', 'route' => 'social-links', 'var' => 'socialLink'],
    ['model' => 'Resume', 'route' => 'resume', 'var' => 'resume'],
    ['model' => 'Setting', 'route' => 'settings', 'var' => 'setting']
];

foreach ($resources as $res) {
    $model = $res['model'];
    $route = $res['route'];
    $var = $res['var'];
    
    // Controller
    $controllerContent = <<<PHP
<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\\$model;
use Illuminate\Http\Request;

class {$model}Controller extends Controller
{
    public function index() {
        \$items = $model::all();
        return view("admin.{$route}.index", compact('items'));
    }
    public function create() {
        return view("admin.{$route}.form");
    }
    public function store(Request \$request) {
        // Basic blanket store for demo
        $model::create(\$request->all());
        return redirect()->route("admin.{$route}.index")->with('success', 'Created successfully.');
    }
    public function edit(\$id) {
        \$item = $model::findOrFail(\$id);
        return view("admin.{$route}.form", compact('item'));
    }
    public function update(Request \$request, \$id) {
        \$item = $model::findOrFail(\$id);
        \$item->update(\$request->all());
        return redirect()->route("admin.{$route}.index")->with('success', 'Updated successfully.');
    }
    public function destroy(\$id) {
        $model::destroy(\$id);
        return redirect()->route("admin.{$route}.index")->with('success', 'Deleted successfully.');
    }
}
PHP;

    file_put_contents("app/Http/Controllers/Admin/{$model}Controller.php", $controllerContent);

    // Views
    @mkdir("resources/views/admin/{$route}", 0777, true);
    
    $indexView = <<<HTML
@extends('layouts.admin')
@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ ucfirst('{$route}') }}</h1>
    <a href="{{ route('admin.{$route}.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Add New</a>
</div>
<div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Details</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-900 dark:text-gray-100">
            @foreach(\$items as \$item)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ \$item->id }}</td>
                <td class="px-6 py-4">{{ \$item->title ?? \$item->name ?? \$item->key ?? \$item->platform ?? 'Item' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="{{ route('admin.{$route}.edit', \$item->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
HTML;
    file_put_contents("resources/views/admin/{$route}/index.blade.php", $indexView);

    $formView = <<<HTML
@extends('layouts.admin')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ isset(\$item) ? 'Edit' : 'Create' }}</h1>
    </div>
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <form action="{{ isset(\$item) ? route('admin.{$route}.update', \$item->id) : route('admin.{$route}.store') }}" method="POST">
            @csrf
            @if(isset(\$item)) @method('PUT') @endif
            <p class="text-sm text-gray-500 mb-4">Edit fields dynamically (placeholder for comprehensive forms).</p>
            <!-- Form fields go here based on model -->
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Save</button>
        </form>
    </div>
</div>
@endsection
HTML;
    file_put_contents("resources/views/admin/{$route}/form.blade.php", $formView);
}

// Write Profile Controller and View manually since it's a singleton
$profileControllerContent = <<<PHP
<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit() {
        \$item = Profile::first() ?? new Profile();
        return view("admin.profile.form", compact('item'));
    }
    public function update(Request \$request) {
        \$item = Profile::first() ?? new Profile();
        \$item->fill(\$request->all());
        \$item->save();
        return redirect()->route("admin.profile.edit")->with('success', 'Updated successfully.');
    }
}
PHP;
file_put_contents("app/Http/Controllers/Admin/ProfileController.php", $profileControllerContent);

@mkdir("resources/views/admin/profile", 0777, true);
$profileFormView = <<<HTML
@extends('layouts.admin')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit Profile</h1>
    </div>
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <form action="{{ route('admin.profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                <input type="text" name="name" value="{{ \$item->name ?? '' }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Primary Role</label>
                <input type="text" name="primary_role" value="{{ \$item->primary_role ?? '' }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Save Profile</button>
        </form>
    </div>
</div>
@endsection
HTML;
file_put_contents("resources/views/admin/profile/form.blade.php", $profileFormView);

echo "Done.";
