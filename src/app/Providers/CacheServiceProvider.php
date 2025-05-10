<?php

namespace App\Providers;

use App\Services\CacheService;
use App\Services\Interfaces\CacheServiceInterface;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(CacheServiceInterface::class, function ($app) {
            return new CacheService(
                config('cache.ttl.default', 60 * 60), //1h
                config('cache.prefix', 'app_')
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
