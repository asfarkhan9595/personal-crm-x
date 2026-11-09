<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query();

        // Search Filter
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            if ($request->input('status') === 'active') {
                $query->where('is_active', true);
            } elseif ($request->input('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sort order
        $sort = $request->input('sort', 'sort_order_asc');
        switch ($sort) {
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'sort_order_desc':
                $query->orderBy('sort_order', 'desc');
                break;
            case 'sort_order_asc':
            default:
                $query->orderBy('sort_order', 'asc')->orderBy('title', 'asc');
                break;
        }

        $perPage = (int) $request->input('per_page', 5);
        if ($perPage < 1 || $perPage > 100) $perPage = 5;

        $items = $query->paginate($perPage)->withQueryString();

        return view('admin.services.index', compact('items', 'perPage'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        try {
            $baseSlug = Str::slug($request->title);
            $slug = $baseSlug ?: 'service';
            $count = 1;
            while (Service::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$count}";
                $count++;
            }

            Service::create([
                'title' => trim($request->title),
                'slug' => $slug,
                'description' => trim($request->description),
                'icon' => $request->icon ? trim($request->icon) : null,
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->has('is_active'),
            ]);

            return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.services.index')->with('error', 'Failed to create service: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        try {
            $service = Service::findOrFail($id);

            $baseSlug = Str::slug($request->title);
            $slug = $baseSlug ?: 'service';
            $count = 1;
            while (Service::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = "{$baseSlug}-{$count}";
                $count++;
            }

            $service->update([
                'title' => trim($request->title),
                'slug' => $slug,
                'description' => trim($request->description),
                'icon' => $request->icon ? trim($request->icon) : null,
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->has('is_active'),
            ]);

            return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.services.index')->with('error', 'Failed to update service: ' . $e->getMessage());
        }
    }

    public function toggleActive($id)
    {
        try {
            $service = Service::findOrFail($id);
            $service->update(['is_active' => !$service->is_active]);

            $statusText = $service->is_active ? 'Active' : 'Inactive';
            return redirect()->back()->with('success', "'{$service->title}' is now {$statusText}.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $service = Service::findOrFail($id);
            $serviceTitle = $service->title;
            $service->delete();

            return redirect()->route('admin.services.index')->with('success', "Service '{$serviceTitle}' deleted successfully.");
        } catch (\Exception $e) {
            return redirect()->route('admin.services.index')->with('error', 'Failed to delete service: ' . $e->getMessage());
        }
    }
}

