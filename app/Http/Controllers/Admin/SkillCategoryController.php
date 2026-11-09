<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SkillCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SkillCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = SkillCategory::with(['skills:id,name,category_id'])->withCount('skills');

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
            case 'skills_desc':
                $query->orderBy('skills_count', 'desc');
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

        return view('admin.skill-categories.index', compact('items', 'perPage'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:skill_categories,slug'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['slug'] = (!empty($data['slug'])) ? $data['slug'] : Str::slug($data['name']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        SkillCategory::create($data);

        return redirect()->route('admin.skill-categories.index')
            ->with('success', 'Skill category created successfully.');
    }

    public function update(Request $request, $id)
    {
        $category = SkillCategory::findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:skill_categories,slug,' . $category->id],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['slug'] = (!empty($data['slug'])) ? $data['slug'] : Str::slug($data['name']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        $category->update($data);

        return redirect()->route('admin.skill-categories.index')
            ->with('success', 'Skill category updated successfully.');
    }

    public function toggleActive($id)
    {
        $category = SkillCategory::findOrFail($id);
        $category->update(['is_active' => !$category->is_active]);

        $statusText = $category->is_active ? 'Active' : 'Inactive';
        return redirect()->back()->with('success', "Skill category '{$category->name}' is now {$statusText}.");
    }

    public function destroy($id)
    {
        $category = SkillCategory::with(['skills'])->withCount('skills')->findOrFail($id);

        if ($category->skills_count > 0) {
            $linkedSkills = $category->skills->pluck('name')->take(3)->implode(', ');
            $moreCount = $category->skills_count > 3 ? ' and ' . ($category->skills_count - 3) . ' more' : '';

            return redirect()->route('admin.skill-categories.index')
                ->with('error', "Cannot delete skill category '{$category->name}' because it has {$category->skills_count} linked skill(s) [ {$linkedSkills}{$moreCount} ]. Please edit or reassign those skills first.");
        }

        $category->delete();

        return redirect()->route('admin.skill-categories.index')
            ->with('success', 'Skill category deleted successfully.');
    }
}
