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
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-code-generator-swagger');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-code-generator-swagger');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('laravel-code-generator-swagger.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-code-generator-swagger'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-code-generator-swagger'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-code-generator-swagger'),
            ], 'lang');*/

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

        // Register the main class to use with the facade
        $this->app->singleton('laravel-code-generator-swagger', function () {
            return new LaravelCodeGeneratorSwagger;
        });
    }
}
