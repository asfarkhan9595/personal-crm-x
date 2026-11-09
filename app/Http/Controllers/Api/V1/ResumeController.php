<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use App\Http\Resources\ResumeResource;

class ResumeController extends Controller
{
    public function index()
    {
        $resume = Resume::latest()->first();

        return response()->json([
            'success' => true,
            'data' => $resume ? new ResumeResource($resume) : null
        ]);
    }
}

