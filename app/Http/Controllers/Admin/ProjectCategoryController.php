<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ProjectCategory::with(['projects:id,title,category_id'])->withCount('projects');

        // Search Filter
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where('name', 'like', "%{$search}%");
        }

        // Status Filter
        if ($request->filled('status')) {
            if ($request->input('status') === 'active') {
                $query->where('is_active', true);
            } elseif ($request->input('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sort Order
        $sort = $request->input('sort', 'sort_order_asc');
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'projects_desc':
                $query->orderBy('projects_count', 'desc');
                break;
            case 'sort_order_desc':
                $query->orderBy('sort_order', 'desc');
                break;
            case 'sort_order_asc':
            default:
                $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
                break;
        }

        $perPage = (int) $request->input('per_page', 5);
        if ($perPage < 1 || $perPage > 100) $perPage = 5;

        $items = $query->paginate($perPage)->withQueryString();

        return view('admin.project-categories.index', compact('items', 'perPage'));
    }

    public function create()
    {
        return redirect()->route('admin.project-categories.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:project_categories,slug'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['slug'] = (!empty($data['slug'])) ? $data['slug'] : Str::slug($data['name']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        ProjectCategory::create($data);

        return redirect()->route('admin.project-categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(ProjectCategory $projectCategory)
    {
        return redirect()->route('admin.project-categories.index');
    }

    public function update(Request $request, ProjectCategory $projectCategory)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:project_categories,slug,' . $projectCategory->id],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['slug'] = (!empty($data['slug'])) ? $data['slug'] : Str::slug($data['name']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        $projectCategory->update($data);

        return redirect()->route('admin.project-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function toggleActive($id)
    {
        $category = ProjectCategory::findOrFail($id);
        $category->update(['is_active' => !$category->is_active]);

        $statusText = $category->is_active ? 'Active' : 'Inactive';
        return redirect()->back()->with('success', "Category '{$category->name}' is now {$statusText}.");
    }

    public function destroy(ProjectCategory $projectCategory)
    {
        $projectsCount = $projectCategory->projects()->count();
        if ($projectsCount > 0) {
            $linkedTitles = $projectCategory->projects->pluck('title')->take(3)->implode(', ');
            $moreCount = $projectsCount > 3 ? ' and ' . ($projectsCount - 3) . ' more' : '';

            return redirect()->route('admin.project-categories.index')
                ->with('error', "Cannot delete category '{$projectCategory->name}' because it has {$projectsCount} linked project(s) [ {$linkedTitles}{$moreCount} ]. Please edit or reassign those projects first.");
        }

        $projectCategory->delete();

        return redirect()->route('admin.project-categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
