<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with(['category', 'technologies', 'features', 'images'])
            ->where('is_published', true);

        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category)
                  ->orWhere('id', $request->category);
            });
        }

        if ($request->has('featured') && $request->featured == 'true') {
            $query->where('is_featured', true);
        }

        $projects = $query->orderBy('published_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => ProjectResource::collection($projects)
        ]);
    }

    public function show($identifier)
    {
        $project = Project::with(['category', 'technologies', 'features', 'images'])
            ->where('is_published', true)
            ->where(function ($q) use ($identifier) {
                $q->where('id', $identifier)
                  ->orWhere('slug', $identifier);
            })
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => new ProjectResource($project)
        ]);
    }
}

