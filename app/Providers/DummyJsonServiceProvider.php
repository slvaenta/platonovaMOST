<?php

namespace App\Providers;

use App\Services\DummyJsonService;
use GuzzleHttp\Client;
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
        $this->app->singleton('dummyJson', function($app){
            $client = new Client();
            return new DummyJsonService($client);
        });
    }
}
