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
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'partner.active' => \App\Http\Middleware\EnsurePartnerIsActive::class,
    ]);

    $middleware->validateCsrfTokens(except: [
        'midtrans/callback',
        'midtrans/*',
    ]);

    $middleware->redirectGuestsTo(function ($request) {
        if ($request->is('admin') || $request->is('admin/*')) {
            return route('admin.login');
        }

        if ($request->is('partner') || $request->is('partner/*')) {
            return route('partner.login');
        }

        return route('login');
    });
})
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();