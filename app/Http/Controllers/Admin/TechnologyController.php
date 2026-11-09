<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Technology;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TechnologyController extends Controller
{
    public function index(Request $request) {
        $query = Technology::with(['projects:id,title'])->withCount('projects');

        // Search filter
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where('name', 'like', "%{$search}%");
        }

        // Sorting
        $sort = $request->input('sort', 'name_asc');
        switch ($sort) {
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'projects_desc':
                $query->orderBy('projects_count', 'desc');
                break;
            case 'projects_asc':
                $query->orderBy('projects_count', 'asc');
                break;
            case 'name_asc':
            default:
                $query->orderBy('name', 'asc');
                break;
        }

        $perPage = (int) $request->input('per_page', 5);
        if ($perPage < 1 || $perPage > 100) $perPage = 5;

        $items = $query->paginate($perPage)->withQueryString();

        return view("admin.technologies.index", compact('items', 'sort', 'perPage'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255|unique:technologies,name',
            'url' => 'nullable|url',
        ]);

        Technology::create([
            'name' => trim($request->name),
            'slug' => Str::slug($request->name),
            'url' => $request->url,
        ]);

        return redirect()->route('admin.technologies.index')->with('success', 'Technology added successfully.');
    }

    public function update(Request $request, $id) {
        $tech = Technology::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:technologies,name,' . $tech->id,
            'url' => 'nullable|url',
        ]);

        $tech->update([
            'name' => trim($request->name),
            'slug' => Str::slug($request->name),
            'url' => $request->url,
        ]);

        return redirect()->route('admin.technologies.index')->with('success', 'Technology updated successfully.');
    }

    public function destroy($id) {
        $tech = Technology::with(['projects'])->withCount('projects')->findOrFail($id);
        
        if ($tech->projects_count > 0) {
            $linkedProjects = $tech->projects->pluck('title')->take(3)->implode(', ');
            $moreCount = $tech->projects_count > 3 ? ' and ' . ($tech->projects_count - 3) . ' more' : '';
            
            return redirect()->route('admin.technologies.index')->with(
                'error',
                "Cannot delete '{$tech->name}' because it is currently linked to {$tech->projects_count} project(s) [ {$linkedProjects}{$moreCount} ]. Please edit the project(s) to uncheck/unlink this technology and save it first, then return here to delete."
            );
        }

        $tech->delete();
        return redirect()->route('admin.technologies.index')->with('success', 'Technology deleted successfully.');
    }
}
