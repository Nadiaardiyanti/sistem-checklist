<?php

use App\Http\Middleware\EnsureRole;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // KSI: route uji koneksi (test.php) tidak dimuat di production.
        web: array_values(array_filter([
            __DIR__.'/../routes/web.php',
            env('APP_ENV', 'production') === 'local' ? __DIR__.'/../routes/test.php' : null,
        ])),
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // KSI: alias otorisasi berbasis peran -> ->middleware('role:admin')
        $middleware->alias([
            'role' => EnsureRole::class,
        ]);

        // KSI: header keamanan HTTP untuk semua respons web.
        $middleware->appendToGroup('web', SecurityHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })
    ->create();
