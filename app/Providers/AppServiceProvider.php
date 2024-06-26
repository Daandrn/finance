<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
        Blade::directive('carbonDate', function ($expression) {
            return "\Carbon\Carbon::parse({$expression})->format('d/m/Y')";
        });
        
        Blade::directive('valueRealFormat', function ($expression) {
            return "number_format($expression, 2, ',', '.')";
        });

        Blade::directive('valueFormat', function ($expression) {
            return "number_format($expression, 2, ',', '')";
        });
    }
}
