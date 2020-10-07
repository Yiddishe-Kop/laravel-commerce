<?php

namespace YiddisheKop\LaravelCommerce\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use YiddisheKop\LaravelCommerce\LaravelCommerceServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase {
  use RefreshDatabase;

  public function setUp(): void {
    parent::setUp();
    // additional setup
    $this->loadMigrationsFrom(__DIR__ . '/migrations');
  }

  protected function getPackageProviders($app) {
    return [LaravelCommerceServiceProvider::class];
  }

  protected function getEnvironmentSetUp($app) {

  }

}
