<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register your middleware for the 'web' group (runs on all web routes)
        $middleware->web(append: [
            \App\Http\Middleware\UpdateRsUserLastSeen::class, // <-- ADDED HERE
        ]);

        // Register route middleware aliases here
        $middleware->alias([
            'check.session' => \App\Http\Middleware\CheckSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
