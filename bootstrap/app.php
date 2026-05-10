<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'is_admin' => \App\Http\Middleware\AdminMiddleware::class,
        'isPetugas' => \App\Http\Middleware\IsPetugas::class,
        'profile.complete' => \App\Http\Middleware\EnsureProfileIsComplete::class,
    ]);
    $middleware->trustProxies(at: '*');
})
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
