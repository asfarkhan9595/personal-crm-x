<?php
$routes = <<<PHP
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\ResumeController;
use App\Http\Controllers\Admin\SettingController;

Route::get('/', function () { return view('welcome'); });

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::resource('projects', ProjectController::class);
    Route::resource('skills', SkillController::class);
    Route::resource('experience', ExperienceController::class);
    Route::resource('social-links', SocialLinkController::class);
    Route::resource('resume', ResumeController::class);
    Route::resource('settings', SettingController::class);
});
PHP;
file_put_contents('routes/web.php', $routes);

$layout = file_get_contents('resources/views/layouts/admin.blade.php');
$layout = str_replace('<a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    Dashboard
                </a>', '<a href="{{ route(\'admin.dashboard\') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    Dashboard
                </a>', $layout);
                
$layout = str_replace('<a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                    <i data-lucide="user" class="w-5 h-5"></i>
                    Profile
                </a>', '<a href="{{ route(\'admin.profile.edit\') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                    <i data-lucide="user" class="w-5 h-5"></i>
                    Profile
                </a>', $layout);
                
$layout = str_replace('<a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                    <i data-lucide="briefcase" class="w-5 h-5"></i>
                    Projects
                </a>', '<a href="{{ route(\'admin.projects.index\') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                    <i data-lucide="briefcase" class="w-5 h-5"></i>
                    Projects
                </a>', $layout);
                
$layout = str_replace('<a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                    <i data-lucide="code" class="w-5 h-5"></i>
                    Skills
                </a>', '<a href="{{ route(\'admin.skills.index\') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                    <i data-lucide="code" class="w-5 h-5"></i>
                    Skills
                </a>', $layout);
                
$layout = str_replace('<a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                    <i data-lucide="award" class="w-5 h-5"></i>
                    Experience
                </a>', '<a href="{{ route(\'admin.experience.index\') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                    <i data-lucide="award" class="w-5 h-5"></i>
                    Experience
                </a>', $layout);
                
$layout = str_replace('<a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                    <i data-lucide="share-2" class="w-5 h-5"></i>
                    Social Links
                </a>', '<a href="{{ route(\'admin.social-links.index\') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                    <i data-lucide="share-2" class="w-5 h-5"></i>
                    Social Links
                </a>', $layout);
                
$layout = str_replace('<a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                    Resume
                </a>', '<a href="{{ route(\'admin.resume.index\') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                    Resume
                </a>', $layout);
                
$layout = str_replace('<a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                    <i data-lucide="settings" class="w-5 h-5"></i>
                    Settings
                </a>', '<a href="{{ route(\'admin.settings.index\') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                    <i data-lucide="settings" class="w-5 h-5"></i>
                    Settings
                </a>', $layout);

file_put_contents('resources/views/layouts/admin.blade.php', $layout);
echo "Routes and layouts updated.";
