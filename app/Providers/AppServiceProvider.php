<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        if ($this->app->environment('production', 'staging')) {
            URL::forceScheme('https');
        }

        if (config('database.default') === 'sqlite') {
            $targetPath = database_path('database.sqlite');
            if (! file_exists($targetPath)) {
                @mkdir(dirname($targetPath), 0755, true);
                @touch($targetPath);
            }
        }
    }
}
