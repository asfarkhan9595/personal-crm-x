<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Http\Resources\ExperienceResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ExperienceController extends Controller
{
    public function index()
    {
        $query = Experience::query();
        
        // Check if is_published column exists to prevent SQL error, as it was requested in the prompt
        // but might not be in the migration
        if (Schema::hasColumn('experiences', 'is_published')) {
            $query->where('is_published', true);
        }

        $experiences = $query->orderBy('start_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => ExperienceResource::collection($experiences)
        ]);
    }
}

