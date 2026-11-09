<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function index(Request $request)
    {
        $query = Experience::query();

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        // Sort Filter
        $sort = $request->input('sort', 'date_desc');
        switch ($sort) {
            case 'date_asc':
                $query->orderBy('start_date', 'asc');
                break;
            case 'sort_order_asc':
                $query->orderBy('sort_order', 'asc');
                break;
            case 'sort_order_desc':
                $query->orderBy('sort_order', 'desc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'date_desc':
            default:
                $query->orderBy('start_date', 'desc');
                break;
        }

        $perPage = (int) $request->input('per_page', 10);
        $items = $query->paginate($perPage)->withQueryString();

        return view('admin.experience.index', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_current'] = $request->has('is_current') ? 1 : 0;
        $validated['is_published'] = $request->has('is_published') ? 1 : 0;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        if ($validated['is_current']) {
            $validated['end_date'] = null;
        }

        Experience::create($validated);

        return redirect()->route('admin.experience.index')->with('success', 'Experience added successfully.');
    }

    public function update(Request $request, $id)
    {
        $item = Experience::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_current'] = $request->has('is_current') ? 1 : 0;
        $validated['is_published'] = $request->has('is_published') ? 1 : 0;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        if ($validated['is_current']) {
            $validated['end_date'] = null;
        }

        $item->update($validated);

        return redirect()->route('admin.experience.index')->with('success', 'Experience updated successfully.');
    }

    public function togglePublished($id)
    {
        $item = Experience::findOrFail($id);
        $item->update(['is_published' => !$item->is_published]);

        return redirect()->back()->with('success', 'Experience status updated successfully.');
    }

    public function destroy($id)
    {
        $item = Experience::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.experience.index')->with('success', 'Experience deleted successfully.');
    }
}
