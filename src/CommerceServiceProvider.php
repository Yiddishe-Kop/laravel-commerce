<?php

namespace YiddisheKop\LaravelCommerce;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CommerceServiceProvider extends ServiceProvider {
  /**
   * Bootstrap the application services.
   */
  public function boot() {
    $this->bootVendorAssets();
    $this->registerRoutes();
    Gateway::bootGateways();
  }

  protected function bootVendorAssets() {

    $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

    if ($this->app->runningInConsole()) {
      $this->publishes([
        __DIR__ . '/../config/commerce.php' => config_path('commerce.php'),
      ], 'config');

      // Registering package commands.
      // $this->commands([]);
    }
  }

  protected function registerRoutes() {
    Route::group($this->routeConfiguration(), function () {
      $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    });
  }

  protected function routeConfiguration() {
    return [
      'prefix' => config('commerce.prefix'),
      'middleware' => config('commerce.middleware'),
    ];
  }

  public $bindings = [
    'gateway' => Gateway::class,
    'cart' => Cart::class,
  ];

  /**
   * Register the application services.
   */
  public function register() {
    // Automatically apply the package configuration
    $this->mergeConfigFrom(__DIR__ . '/../config/commerce.php', 'commerce');

    // $this->app->singleton(Cart::class, function ($app) {
    //   return $app['commerce']->getCart($app);
    // });
  }
}
