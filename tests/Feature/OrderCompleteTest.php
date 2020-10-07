<?php

namespace YiddisheKop\LaravelCommerce\Tests;

use YiddisheKop\LaravelCommerce\Models\Order;
use YiddisheKop\LaravelCommerce\Tests\Models\Product;

class OrderCompleteTest extends TestCase {

  private Order $cart;

  public function setUp(): void {
    parent::setUp();

    $this->cart = Order::create();

    $this->cart
      ->add(Product::create([
        'title' => 'BA Ziporen',
        'price' => 333
      ]), 2)
      ->add(Product::create([
        'title' => 'BA Vilna',
        'price' => 444
      ]), 5);

  }

  /** @test */
  public function it_marks_the_order_as_complete() {

    $this->cart->markAsCompleted();

    $this->assertTrue($this->cart->is_paid);
    $this->assertEquals(Order::STATUS_COMPLETED, $this->cart->status);
    $this->assertTrue(today()->isSameDay($this->cart->paid_date));
  }
}
