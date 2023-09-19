<?php

namespace App\Providers;

use App\Services\DummyJsonService;
use Illuminate\Support\ServiceProvider;

class DummyJsonServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(DummyJsonService::class, function($app){
            return new DummyJsonService();
        });
    }
}
