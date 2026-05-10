<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('layouts.admin', function ($view) {
        $pendingCount = \App\Models\Peminjaman::where('status', 'pending')->count();
        $view->with('pendingCount', $pendingCount);
        Paginator::useTailwind();
    });
    }
}
