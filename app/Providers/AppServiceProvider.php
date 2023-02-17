<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;

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
        // seed database:
        Artisan::call('db:seed');
        // test database connection:
        try {
            \DB::connection()->getPdo();
            // if no error, then database is connected. Print success message
            if (\DB::connection()->getDatabaseName()) {
                echo "connected successfully to database " . \DB::connection()->getDatabaseName();
            }
        } catch (\Exception $e) {
            die("Could not connect to the database.  Please check your configuration. error:" . $e );
        }
    }
}
