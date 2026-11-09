<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Profile;
use App\Models\Setting;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class ProjectFrontendController extends Controller
{
    public function index(Request $request)
    {
        $profile = Profile::where('is_active', true)->first() ?? Profile::first();
        $settings = (object) Setting::all()->pluck('value', 'key')->toArray();
        $socialLinks = SocialLink::where('is_active', true)->orderBy('sort_order', 'asc')->get();

        $categories = ProjectCategory::where('is_active', true)->orderBy('sort_order', 'asc')->get();

        $query = Project::with(['category', 'technologies', 'features', 'images'])
            ->where('is_published', true);

        if ($request->filled('category')) {
            $catSlug = $request->input('category');
            $query->whereHas('category', function($q) use ($catSlug) {
                $q->where('slug', $catSlug);
            });
        }

        $projects = $query
            ->orderBy('featured', 'desc')
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(6)
            ->withQueryString();

        $resolvedTheme = $this->resolveThemeName($request, $settings);
        $view = view()->exists("themes.{$resolvedTheme}.projects.index")
            ? "themes.{$resolvedTheme}.projects.index"
            : (view()->exists("themes.theme1.projects.index") ? "themes.theme1.projects.index" : 'projects.index');

        return view($view, compact('projects', 'categories', 'profile', 'settings', 'socialLinks'));
    }

    public function show(Request $request, $slug)
    {
        $project = Project::with(['category', 'technologies', 'features', 'images'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $project
            ]);
        }

        $profile = Profile::where('is_active', true)->first() ?? Profile::first();
        $settings = (object) Setting::all()->pluck('value', 'key')->toArray();
        $socialLinks = SocialLink::where('is_active', true)->orderBy('sort_order', 'asc')->get();

        $resolvedTheme = $this->resolveThemeName($request, $settings);
        $view = view()->exists("themes.{$resolvedTheme}.projects.show")
            ? "themes.{$resolvedTheme}.projects.show"
            : (view()->exists("themes.theme1.projects.show") ? "themes.theme1.projects.show" : 'projects.show');

        return view($view, compact('project', 'profile', 'settings', 'socialLinks'));
    }
}
