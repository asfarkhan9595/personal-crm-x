<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Profile;
use App\Models\Setting;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $profile = Profile::where('is_active', true)->first() ?? Profile::first();
        $settings = (object) Setting::all()->pluck('value', 'key')->toArray();
        $socialLinks = SocialLink::where('is_active', true)->orderBy('sort_order', 'asc')->get();

        $query = Post::where('is_published', true);

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $posts = $query->orderBy('published_at', 'desc')->orderBy('created_at', 'desc')->paginate(6)->withQueryString();
        $categories = Post::where('is_published', true)->distinct()->pluck('category')->filter()->values();
        $trendingPosts = Post::where('is_published', true)->where('is_trending', true)->take(4)->get();

        $resolvedTheme = $this->resolveThemeName($request, $settings);
        $view = view()->exists("themes.{$resolvedTheme}.blog.index")
            ? "themes.{$resolvedTheme}.blog.index"
            : (view()->exists("themes.theme1.blog.index") ? "themes.theme1.blog.index" : 'blog.index');

        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest' || $request->query('ajax') === '1') {
            $gridPartial = view()->exists("themes.{$resolvedTheme}.blog.partials.posts-grid")
                ? "themes.{$resolvedTheme}.blog.partials.posts-grid"
                : 'themes.theme1.blog.partials.posts-grid';

            return response()->json([
                'html' => view($gridPartial, compact('posts'))->render(),
                'total' => $posts->total()
            ]);
        }

        return view($view, compact('posts', 'categories', 'trendingPosts', 'profile', 'settings', 'socialLinks'));
    }

    public function show(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $profile = Profile::where('is_active', true)->first() ?? Profile::first();
        $settings = (object) Setting::all()->pluck('value', 'key')->toArray();
        $socialLinks = SocialLink::where('is_active', true)->orderBy('sort_order', 'asc')->get();

        // Increment view count
        $post->increment('views_count');

        // Related posts
        $relatedPosts = Post::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->where('category', $post->category)
            ->take(3)
            ->get();

        if ($relatedPosts->count() < 3) {
            $extra = Post::where('is_published', true)
                ->where('id', '!=', $post->id)
                ->whereNotIn('id', $relatedPosts->pluck('id'))
                ->take(3 - $relatedPosts->count())
                ->get();
            $relatedPosts = $relatedPosts->merge($extra);
        }

        $resolvedTheme = $this->resolveThemeName($request, $settings);
        $view = view()->exists("themes.{$resolvedTheme}.blog.show")
            ? "themes.{$resolvedTheme}.blog.show"
            : (view()->exists("themes.theme1.blog.show") ? "themes.theme1.blog.show" : 'blog.show');

        return view($view, compact('post', 'relatedPosts', 'profile', 'settings', 'socialLinks'));
    }
}
