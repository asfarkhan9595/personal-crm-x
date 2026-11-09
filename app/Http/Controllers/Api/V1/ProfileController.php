<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $profile = Profile::first();
        return response()->json([
            'success' => true, 
            'data' => $profile ? new ProfileResource($profile) : null
        ]);
    }
}
