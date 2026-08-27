<?php

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
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
            $dbPath = config('database.connections.sqlite.database');
            if ($dbPath && $dbPath !== ':memory:' && ! str_contains($dbPath, 'database.sqlite')) {
                // Keep relative path resolution clean
            }
            $targetPath = database_path('database.sqlite');
            if (! file_exists($targetPath)) {
                @mkdir(dirname($targetPath), 0755, true);
                @touch($targetPath);
            }
            try {
                if (file_exists($targetPath) && ! Schema::hasTable('users')) {
                    Artisan::call('migrate', ['--force' => true]);
                    Artisan::call('db:seed', ['--force' => true]);
                }
            } catch (\Throwable $e) {
                // Log or ignore if already migrated
            }
        }
    }
}
