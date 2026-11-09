<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProjectFrontendController;
use App\Http\Controllers\ContactFrontendController;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\SkillCategoryController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\ResumeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProjectCategoryController;
use App\Http\Controllers\Admin\TechnologyController;
use App\Http\Controllers\Admin\AdminContactMessageController;
use App\Http\Controllers\Admin\AdminContactSettingsController;
use App\Http\Controllers\Admin\ServiceController;

/*
|--------------------------------------------------------------------------
| Public Portfolio Blade Routes (Server-Rendered, NO API Fetching)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/projects', [ProjectFrontendController::class, 'index'])->name('projects.index');
Route::get('/projects/{slug}', [ProjectFrontendController::class, 'show'])->name('projects.show');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/contact', [ContactFrontendController::class, 'store'])->name('contact.store');
Route::post('/theme/switch', [\App\Http\Controllers\ThemeSwitchController::class, 'switch'])->name('theme.switch');

// Dedicated route for downloading active resume
Route::get('/resume/download', function () {
    $activeResume = \App\Models\Resume::where('is_active', true)->first() ?? \App\Models\Resume::latest()->first();
    
    if ($activeResume && $activeResume->file_path) {
        $path = ltrim($activeResume->file_path, '/');
        
        // It's in public/uploads/resumes/...
        $fullPath = public_path('uploads/' . $path);
        
        if (file_exists($fullPath)) {
            return response()->file($fullPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . \Illuminate\Support\Str::slug($activeResume->title) . '.pdf"',
            ]);
        }
    }
    
    abort(404, 'Active resume PDF not found.');
})->name('resume.download');

// Storage file serving route (for shared hosting like InfinityFree where symlinks are disabled)
Route::get('/storage/{path}', function ($path) {
    $file = storage_path('app/public/' . $path);
    if (!file_exists($file)) {
        abort(404);
    }
    $mime = mime_content_type($file) ?: 'application/octet-stream';
    return response()->file($file, [
        'Content-Type' => $mime,
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->where('path', '.*')->name('storage.serve');

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AuthController::class, 'login'])->name('admin.login.submit')->middleware('throttle:5,1');
Route::post('admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', function() { return redirect()->route('admin.dashboard'); });
    Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard');
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('projects/images/{id}', [ProjectController::class, 'destroyImage'])->name('projects.images.destroy');
    Route::post('projects/images/bulk-delete', [ProjectController::class, 'bulkDestroyImages'])->name('projects.images.bulk-destroy');
    Route::resource('projects', ProjectController::class);
    Route::patch('project-categories/{id}/toggle-active', [ProjectCategoryController::class, 'toggleActive'])->name('project-categories.toggle-active');
    Route::resource('project-categories', ProjectCategoryController::class)->except(['show']);
    Route::resource('technologies', TechnologyController::class)->except(['show']);
    Route::patch('skill-categories/{id}/toggle-active', [SkillCategoryController::class, 'toggleActive'])->name('skill-categories.toggle-active');
    Route::resource('skill-categories', SkillCategoryController::class)->except(['show']);
    Route::patch('skills/{id}/toggle-active', [SkillController::class, 'toggleActive'])->name('skills.toggle-active');
    Route::resource('skills', SkillController::class);
    Route::patch('experience/{id}/toggle-published', [ExperienceController::class, 'togglePublished'])->name('experience.toggle-published');
    Route::resource('experience', ExperienceController::class)->except(['show']);
    Route::patch('social-links/{id}/toggle-active', [SocialLinkController::class, 'toggleActive'])->name('social-links.toggle-active');
    Route::resource('social-links', SocialLinkController::class)->except(['show']);
    Route::patch('resume/{id}/toggle-active', [ResumeController::class, 'toggleActive'])->name('resume.toggle-active');
    Route::resource('resume', ResumeController::class);
    Route::patch('contact-messages/{id}/status', [AdminContactMessageController::class, 'updateStatus'])->name('contact-messages.update-status');
    Route::resource('contact-messages', AdminContactMessageController::class)->only(['index', 'show', 'destroy']);
    Route::get('contact-settings', [AdminContactSettingsController::class, 'index'])->name('contact-settings.index');
    Route::post('contact-settings', [AdminContactSettingsController::class, 'update'])->name('contact-settings.update');
    Route::post('settings/update-all', [SettingController::class, 'updateAll'])->name('settings.update-all');
    Route::post('settings/custom', [SettingController::class, 'storeCustom'])->name('settings.store-custom');
    Route::patch('posts/{post}/toggle-publish', [\App\Http\Controllers\Admin\PostController::class, 'togglePublish'])->name('posts.toggle-publish');
    Route::patch('posts/{post}/toggle-trending', [\App\Http\Controllers\Admin\PostController::class, 'toggleTrending'])->name('posts.toggle-trending');
    Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);
    Route::patch('services/{id}/toggle-active', [ServiceController::class, 'toggleActive'])->name('services.toggle-active');
    Route::resource('services', ServiceController::class)->except(['show']);
    Route::resource('settings', SettingController::class)->only(['index', 'destroy']);

    // CRM Module Routes (Additive & Protected by Feature Flag)
    if (config('crm.enabled', true)) {
        Route::prefix('crm')->name('crm.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\Crm\CrmDashboardController::class, 'index'])->name('dashboard');
            Route::get('dashboard', [\App\Http\Controllers\Admin\Crm\CrmDashboardController::class, 'index'])->name('dashboard.index');

            // Leads
            Route::patch('leads/{id}/status', [\App\Http\Controllers\Admin\Crm\CrmLeadController::class, 'updateStatus'])->name('leads.update-status');
            Route::resource('leads', \App\Http\Controllers\Admin\Crm\CrmLeadController::class);

            // Payments
            Route::get('payments/export/csv', [\App\Http\Controllers\Admin\Crm\CrmPaymentController::class, 'exportCsv'])->name('payments.export-csv');
            Route::post('leads/{lead}/payments', [\App\Http\Controllers\Admin\Crm\CrmPaymentController::class, 'store'])->name('payments.store');
            Route::put('payments/{payment}', [\App\Http\Controllers\Admin\Crm\CrmPaymentController::class, 'update'])->name('payments.update');
            Route::delete('payments/{payment}', [\App\Http\Controllers\Admin\Crm\CrmPaymentController::class, 'destroy'])->name('payments.destroy');
            Route::get('payments', [\App\Http\Controllers\Admin\Crm\CrmPaymentController::class, 'index'])->name('payments.index');

            // Settings & Sync
            Route::get('settings', [\App\Http\Controllers\Admin\Crm\CrmSettingController::class, 'index'])->name('settings.index');
            Route::post('settings', [\App\Http\Controllers\Admin\Crm\CrmSettingController::class, 'update'])->name('settings.update');
            Route::post('settings/resync', [\App\Http\Controllers\Admin\Crm\CrmSettingController::class, 'resyncAll'])->name('settings.resync');
            Route::post('settings/import-sheet', [\App\Http\Controllers\Admin\Crm\CrmSettingController::class, 'importSheet'])->name('settings.import-sheet');
        });
    }
});

/*
|--------------------------------------------------------------------------
| Direct storage fallback route (InfinityFree / Shared Hosting friendly)
|--------------------------------------------------------------------------
*/
Route::get('storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        $filePath = storage_path('app/' . $path);
    }
    if (file_exists($filePath)) {
        $mime = @mime_content_type($filePath) ?: 'application/octet-stream';
        return response()->file($filePath, [
            'Content-Type' => $mime,
            'Access-Control-Allow-Origin' => '*',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
    abort(404, 'Requested storage file not found.');
})->where('path', '.*');
