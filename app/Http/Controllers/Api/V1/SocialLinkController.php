<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use App\Http\Resources\SocialLinkResource;

class SocialLinkController extends Controller
{
    public function index()
    {
        $links = SocialLink::orderBy('sort_order')->get();

        return response()->json([
            'success' => true,
            'data' => SocialLinkResource::collection($links)
        ]);
    }
}

