<?php

namespace YiddisheKop\LaravelCommerce\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use YiddisheKop\LaravelCommerce\LaravelCommerceServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase {
  use RefreshDatabase;

  public function setUp(): void {
    parent::setUp();
    // additional setup
  }

  protected function getPackageProviders($app) {
    return [LaravelCommerceServiceProvider::class];
  }

  protected function getEnvironmentSetUp($app) {
    include_once __DIR__ . '/../database/migrations/create_orders_table.php.stub';
    include_once __DIR__ . '/../database/migrations/create_order_items_table.php.stub';
    include_once __DIR__ . '/migrations/create_products_table.php';

    (new \CreateOrdersTable)->up();
    (new \CreateOrderItemsTable)->up();
    (new \CreateProductsTable)->up();
  }

}
