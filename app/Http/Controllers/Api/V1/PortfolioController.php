<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Project;
use App\Models\SkillCategory;
use App\Models\Experience;
use App\Models\Resume;
use App\Models\SocialLink;
use App\Http\Resources\SocialLinkResource;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    protected function getBaseUrl(Request $request): string
    {
        $url = $request->schemeAndHttpHost();
        if (config('app.env') === 'production' || $request->header('X-Forwarded-Proto') === 'https') {
            return preg_replace('/^http:/i', 'https:', $url);
        }
        return $url;
    }

    public function profile(Request $request)
    {
        $baseUrl = $this->getBaseUrl($request);
        $profile = Profile::first();
        $activeResume = Resume::where('is_active', true)->first();

        if ($profile) {
            $profile->secondary_roles = is_string($profile->secondary_roles) ? json_decode($profile->secondary_roles, true) : $profile->secondary_roles;
            $profile->resume_url = $activeResume ? format_image_url($activeResume->file_path) : null;
            if (isset($profile->show_profile_image) && !$profile->show_profile_image) {
                $profile->profile_image = null;
            } elseif ($profile->profile_image && !str_starts_with($profile->profile_image, 'http')) {
                $profile->profile_image = format_image_url($profile->profile_image);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $profile
        ])->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
    }

    public function projects(Request $request)
    {
        $query = Project::with(['category', 'features', 'technologies', 'images'])
            ->where('is_published', true)
            ->whereHas('category', function($q) {
                $q->where('is_active', true);
            });

        if ($request->has('featured')) {
            $query->where('featured', filter_var($request->featured, FILTER_VALIDATE_BOOLEAN));
        }
        if ($request->has('category')) {
            $query->whereHas('category', function($q) use($request) {
                $q->where('slug', $request->category);
            });
        }
        $projects = $query->orderBy('sort_order')->get()->map(function($p) use ($request) {
            $this->transformImageUrls($p, $request);
            return $p;
        });

        return response()->json([
            'success' => true,
            'data' => $projects
        ])->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
    }

    public function project(Request $request, $slug)
    {
        $project = Project::with(['category', 'features', 'technologies', 'images'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->whereHas('category', function($q) {
                $q->where('is_active', true);
            })
            ->firstOrFail();

        $this->transformImageUrls($project, $request);

        return response()->json([
            'success' => true,
            'data' => $project
        ])->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
    }

    protected function transformImageUrls($project, Request $request)
    {
        $baseUrl = $this->getBaseUrl($request);

        if ($project->cover_image && !str_starts_with($project->cover_image, 'http')) {
            $project->cover_image = format_image_url($project->cover_image);
        }
        if ($project->images) {
            foreach ($project->images as $img) {
                if (!str_starts_with($img->image_path, 'http')) {
                    $img->image_path = format_image_url($img->image_path);
                }
            }
        }
    }

    public function skills()
    {
        return response()->json([
            'success' => true,
            'data' => SkillCategory::with(['skills' => function($q) {
                $q->where('is_active', true)->orderBy('sort_order');
            }])->orderBy('sort_order')->get()
        ])->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
    }

    public function experience()
    {
        return response()->json([
            'success' => true,
            'data' => Experience::where('is_published', true)->orderBy('sort_order')->get()
        ])->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
    }

    public function socialLinks()
    {
        $links = SocialLink::where('is_active', true)->orderBy('sort_order', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => SocialLinkResource::collection($links)
        ])->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
    }

    public function posts(Request $request)
    {
        $baseUrl = $this->getBaseUrl($request);
        $query = \App\Models\Post::published();

        if ($request->filled('category') && $request->category !== 'All') {
            $query->where('category', $request->category);
        }

        if ($request->has('trending') && filter_var($request->trending, FILTER_VALIDATE_BOOLEAN)) {
            $query->where('is_trending', true);
        }

        if ($request->has('featured') && filter_var($request->featured, FILTER_VALIDATE_BOOLEAN)) {
            $query->where('is_featured', true);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->latest('published_at')
            ->get()
            ->map(function ($post) use ($baseUrl) {
                if ($post->cover_image && !str_starts_with($post->cover_image, 'http')) {
                    $post->cover_image = format_image_url($post->cover_image);
                }
                if ($post->og_image && !str_starts_with($post->og_image, 'http')) {
                    $post->og_image = format_image_url($post->og_image);
                }
                return $post;
            });

        return response()->json([
            'success' => true,
            'data' => $posts
        ])->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
    }

    public function post(Request $request, $slug)
    {
        $baseUrl = $this->getBaseUrl($request);
        $post = \App\Models\Post::published()->where('slug', $slug)->firstOrFail();
        $post->increment('views_count');

        if ($post->cover_image && !str_starts_with($post->cover_image, 'http')) {
            $post->cover_image = format_image_url($post->cover_image);
        }
        if ($post->og_image && !str_starts_with($post->og_image, 'http')) {
            $post->og_image = format_image_url($post->og_image);
        }

        // Fetch prev and next posts for seamless article navigation
        $prevPost = \App\Models\Post::published()
            ->where('published_at', '<', $post->published_at)
            ->latest('published_at')
            ->first(['title', 'slug', 'category']);

        $nextPost = \App\Models\Post::published()
            ->where('published_at', '>', $post->published_at)
            ->oldest('published_at')
            ->first(['title', 'slug', 'category']);

        $postData = $post->toArray();
        $postData['prev_post'] = $prevPost;
        $postData['next_post'] = $nextPost;

        return response()->json([
            'success' => true,
            'data' => $postData
        ])->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
    }

    public function settings(Request $request)
    {
        $defaults = [
            'site_name' => 'Asfar Khan — Portfolio',
            'site_description' => 'Asfar Khan is a Python & AI Automation Developer building practical AI applications, APIs, Chrome extensions, and automation tools.',
            'site_url' => 'http://localhost:5173',
            'site_logo' => '',
            'site_favicon' => '',
            'default_theme' => 'system',
            'active_theme' => 'modern',
            'site_theme_preset' => 'classic',
            'primary_color' => '#10b981',
            'enable_dark_mode' => '1',
            'timezone' => 'Asia/Kolkata',
            'maintenance_mode' => '0',
        ];

        $dbSettings = \App\Models\Setting::whereIn('key', array_keys($defaults))->pluck('value', 'key')->toArray();

        foreach ($defaults as $k => $v) {
            if (isset($dbSettings[$k])) {
                $defaults[$k] = $dbSettings[$k];
            }
        }

        $baseUrl = $this->getBaseUrl($request);
        foreach (['site_logo', 'site_favicon'] as $imgKey) {
            if (!empty($defaults[$imgKey]) && !str_starts_with($defaults[$imgKey], 'http')) {
                $defaults[$imgKey] = format_image_url($defaults[$imgKey]);
            }
        }

        return response()->json([
            'success' => true,
            'data' => (object) $defaults
        ])->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
    }
}
