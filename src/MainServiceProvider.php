<?php

namespace Totti619\Gallery;

use Illuminate\Support\ServiceProvider;

class MainServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //

        $this->publishes([
            // Publish the config file.
            __DIR__ . '/config/gallery.php' => config_path('gallery.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/assets/img/gallery' => public_path('vendor/totti619/gallery/img'),
            __DIR__.'/assets/meta' => public_path('vendor/totti619/gallery/meta'),
        ], 'public');

        $this->publishes([
            // Publish the translations folder.
            __DIR__ . '/lang' => resource_path('lang/vendor/totti619/gallery'),
            // Publish the views.
            __DIR__ . '/views' => resource_path('views/vendor/totti619/gallery'),
        ], 'resources');

        // Load the routes for caching
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        // Load the translations folder.
        $this->loadTranslationsFrom('lang/vendor/totti619/gallery', 'gallery');

        // Load the views.
        $this->loadViewsFrom(resource_path('views/vendor/totti619/gallery'), 'gallery');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        // Register our controller
        $this->app->make('Totti619\Gallery\Libraries\Controllers\MainController');

        // Makes user to only edit the configuration if overrides it.
        $this->mergeConfigFrom(
            __DIR__ . '/config/gallery.php', 'gallery'
        );
    }
}
