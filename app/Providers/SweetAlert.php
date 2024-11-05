<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use RealRashid\SweetAlert\SweetAlertServiceProvider;

class SweetAlert extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        SweetAlertServiceProvider::class;
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
