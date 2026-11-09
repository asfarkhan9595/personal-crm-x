<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'content' => $this->content,
            'url' => $this->url,
            'github_url' => $this->github_url,
            'is_featured' => $this->is_featured,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at,
            'category' => $this->whenLoaded('category'),
            'technologies' => $this->whenLoaded('technologies'),
            'features' => $this->whenLoaded('features'),
            'images' => $this->whenLoaded('images'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

