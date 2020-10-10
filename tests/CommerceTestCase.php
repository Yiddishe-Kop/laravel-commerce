<?php

namespace YiddisheKop\LaravelCommerce\Tests;

use YiddisheKop\LaravelCommerce\Models\Order;
use YiddisheKop\LaravelCommerce\Tests\Fixtures\Product;
use YiddisheKop\LaravelCommerce\Tests\TestCase;

class CommerceTestCase extends TestCase {

  protected Order $cart;
  protected Product $product;

  protected function setUp(): void {
    parent::setUp();

    Order::create();
    Product::create([
      'title' => 'BA Ziporen',
      'price' => 333
    ]);

    $this->cart = Order::first();
    $this->product = Product::first();
  }

}
