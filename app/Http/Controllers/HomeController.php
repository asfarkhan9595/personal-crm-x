<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\SkillCategory;
use App\Models\Project;
use App\Models\Experience;
use App\Models\Post;
use App\Models\SocialLink;
use App\Models\Setting;
use App\Models\ContactSetting;
use App\Models\Resume;
use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $profile = Profile::where('is_active', true)->first() ?? Profile::first();
        
        $services = Service::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $skillCategories = SkillCategory::with(['skills' => function($query) {
            $query->where('is_active', true)->orderBy('sort_order', 'asc');
        }])->where('is_active', true)->orderBy('sort_order', 'asc')->get();

        $projects = Project::with(['category', 'technologies', 'features', 'images'])
            ->where('is_published', true)
            ->orderBy('featured', 'desc')
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $totalProjectsCount = Project::where('is_published', true)->count();

        $experience = Experience::where('is_published', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('start_date', 'desc')
            ->get();

        $posts = Post::where('is_published', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $socialLinks = SocialLink::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $contactSettingsArray = ContactSetting::all()->pluck('value', 'key')->toArray();
        $contactSettings = (object) $contactSettingsArray;

        $settingsQuery = Setting::all()->pluck('value', 'key')->toArray();
        $settings = (object) array_merge($settingsQuery, $contactSettingsArray);

        $activeResume = Resume::where('is_active', true)->first() ?? Resume::latest()->first();

        // Maintenance mode check
        $isMaintenance = isset($settings->maintenance_mode) && 
            in_array(strtolower((string)$settings->maintenance_mode), ['1', 'true', 'yes']);
            
        $isPreview = $request->query('preview') === '1';

        if ($isMaintenance && !$isPreview) {
            return view('maintenance', compact('settings', 'profile'));
        }

        // Resolve active theme view: theme1, theme2, or theme3
        $resolvedTheme = $this->resolveThemeName($request, $settings);
        $viewName = view()->exists("themes.{$resolvedTheme}.home") ? "themes.{$resolvedTheme}.home" : 'home';

        return view($viewName, compact(
            'profile',
            'services',
            'skillCategories',
            'projects',
            'totalProjectsCount',
            'experience',
            'posts',
            'socialLinks',
            'contactSettings',
            'settings',
            'activeResume'
        ));
    }
}
