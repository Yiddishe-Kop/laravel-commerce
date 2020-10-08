<?php

namespace YiddisheKop\LaravelCommerce\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use YiddisheKop\LaravelCommerce\CommerceServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase {
  use RefreshDatabase;

  public function setUp(): void {
    parent::setUp();
    // additional setup
    $this->loadLaravelMigrations();
  }

  protected function getPackageProviders($app) {
    return [CommerceServiceProvider::class];
  }

  protected function getEnvironmentSetUp($app) {
    include_once __DIR__ . '/../database/migrations/create_orders_table.php.stub';
    include_once __DIR__ . '/../database/migrations/create_order_items_table.php.stub';
    include_once __DIR__ . '/Fixtures/create_products_table.php';

    (new \CreateOrdersTable)->up();
    (new \CreateOrderItemsTable)->up();
    (new \CreateProductsTable)->up();
  }

}
