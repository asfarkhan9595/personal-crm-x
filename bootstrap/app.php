<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectTo(
            guests: '/admin/login',
            users: '/admin/dashboard'
        );
        $middleware->web(append: [
            \App\Http\Middleware\CheckMaintenanceMode::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (\Throwable $e, Request $request) {
            if ($e instanceof QueryException || $e instanceof \PDOException) {
                if ($request->is('api/*') || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Database connection failed. Please ensure the database server is running.',
                    ], 503);
                }
                if (!config('app.debug')) {
                    return response()->view('errors.500', [], 503);
                }
            }
        });
    })->create();
