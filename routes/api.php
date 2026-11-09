<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\PortfolioController;
use App\Http\Controllers\Api\V1\ContactController;

Route::prefix('v1')->group(function () {
    Route::get('/profile', [PortfolioController::class, 'profile']);
    Route::get('/projects', [PortfolioController::class, 'projects']);
    Route::get('/projects/{slug}', [PortfolioController::class, 'project']);
    Route::get('/skills', [PortfolioController::class, 'skills']);
    Route::get('/experience', [PortfolioController::class, 'experience']);
    Route::get('/social-links', [PortfolioController::class, 'socialLinks']);
    Route::get('/posts', [PortfolioController::class, 'posts']);
    Route::get('/posts/{slug}', [PortfolioController::class, 'post']);
    Route::get('/settings', [PortfolioController::class, 'settings']);
    Route::get('/resume/download', function () {
        $activeResume = \App\Models\Resume::where('is_active', true)->first() ?? \App\Models\Resume::latest()->first();
        if ($activeResume && $activeResume->file_path) {
            $path = ltrim($activeResume->file_path, '/');
            $fullPath = storage_path('app/public/' . $path);
            if (!file_exists($fullPath)) {
                $fullPath = storage_path('app/' . $path);
            }
            if (file_exists($fullPath)) {
                return response()->file($fullPath, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . \Illuminate\Support\Str::slug($activeResume->title) . '.pdf"',
                    'Access-Control-Allow-Origin' => '*',
                ]);
            }
        }
        return response()->json(['success' => false, 'message' => 'Active resume not found'], 404);
    });
    Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1');
    Route::get('/contact-settings', [ContactController::class, 'settings']);
});
