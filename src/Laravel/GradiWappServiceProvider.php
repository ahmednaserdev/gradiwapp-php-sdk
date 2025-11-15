<?php

namespace GradiWapp\Sdk\Laravel;

use GradiWapp\Sdk\Client;
use GradiWapp\Sdk\Config;
use Illuminate\Support\ServiceProvider;

/**
 * Laravel Service Provider for GradiWapp SDK
 */
class GradiWappServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge default config
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/gradiwapp.php',
            'gradiwapp'
        );

        // Bind Client as singleton
        $this->app->singleton(Client::class, function ($app) {
            $config = new Config(
                config('gradiwapp.base_url', ''),
                config('gradiwapp.api_key', ''),
                config('gradiwapp.api_secret', ''),
                config('gradiwapp.timeout', 30),
                config('gradiwapp.max_retries', 1),
                config('gradiwapp.verify_ssl', true)
            );

            return new Client($config);
        });

        // Alias for easy access
        $this->app->alias(Client::class, 'gradiwapp.client');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish config file
        $this->publishes([
            __DIR__ . '/../../config/gradiwapp.php' => config_path('gradiwapp.php'),
        ], 'config');
    }
}

