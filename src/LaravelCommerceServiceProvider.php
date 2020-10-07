<?php

namespace YiddisheKop\LaravelCommerce;

use Illuminate\Support\ServiceProvider;

class LaravelCommerceServiceProvider extends ServiceProvider {
  /**
   * Bootstrap the application services.
   */
  public function boot() {
    $this->bootVendorAssets();
    LaravelCommerce::bootGateways();
  }

  protected function bootVendorAssets() {
    /*
    * Optional methods to load your package assets
    */
    // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-commerce');
    // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-commerce');
    $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    // $this->loadRoutesFrom(__DIR__.'/routes.php');

    if ($this->app->runningInConsole()) {
      $this->publishes([
        __DIR__ . '/../config/commerce.php' => config_path('commerce.php'),
      ], 'config');

      // Publishing the views.
      /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-commerce'),
            ], 'views');*/

      // Publishing assets.
      /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-commerce'),
            ], 'assets');*/

      // Publishing the translation files.
      /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-commerce'),
            ], 'lang');*/

      // Registering package commands.
      // $this->commands([]);
    }
  }

  /**
   * Register the application services.
   */
  public function register() {
    // Automatically apply the package configuration
    $this->mergeConfigFrom(__DIR__ . '/../config/commerce.php', 'commerce');

    // Register the main class to use with the facade
    $this->app->singleton('laravel-commerce', function () {
      return new LaravelCommerce;
    });
  }
}
