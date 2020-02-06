<?php

namespace Renepardon\LaravelCodeGeneratorSwagger;

use Illuminate\Support\ServiceProvider;
use Renepardon\LaravelCodeGeneratorSwagger\Console\Command\Api\OpenApi\Parameter;
use Renepardon\LaravelCodeGeneratorSwagger\Console\Command\Api\OpenApi\Response;
use Renepardon\LaravelCodeGeneratorSwagger\Console\Command\Api\OpenApi\Schema;

class LaravelCodeGeneratorSwaggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('laravel-code-generator-swagger.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../resources/templates/openapi' => base_path('resources/laravel-code-generator/templates/openapi'),
            ], 'openapi-template');

            // Registering package commands.
            $this->commands([
                Parameter::class,
                Response::class,
                Schema::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-code-generator-swagger');
    }
}
