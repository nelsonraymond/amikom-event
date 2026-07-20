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
            'midtrans/callback', // Removed the leading slash '/'
            'midtrans/*',        // Alternatively, use a wildcard to cover everything under midtrans
        ]);
    
        $middleware->redirectGuestsTo('/admin/login');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();