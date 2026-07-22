<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->environment('production')) {
        \URL::forceScheme('https');
    }
        View::composer('layouts.app', function ($view) {
            $view->with('categories', Category::orderBy('name')->get());
        });
    }
}