<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Http\Resources\SkillResource;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::with('category')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => SkillResource::collection($skills)
        ]);
    }
}

