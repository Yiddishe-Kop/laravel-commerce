<?php

use YiddisheKop\LaravelCommerce\Models\Order;
use YiddisheKop\LaravelCommerce\Tests\Fixtures\Product;

beforeEach(function () {
  $this->cart
    ->add(Product::create([
      'title' => 'BA Ziporen',
      'price' => 333
    ]), 2)
    ->add(Product::create([
      'title' => 'BA Vilna',
      'price' => 444
    ]), 5);
});

it('marks the order as complete', function () {
  $this->cart->markAsCompleted();
  $this->assertTrue($this->cart->is_paid);
  $this->assertEquals(Order::STATUS_COMPLETED, $this->cart->status);
  $this->assertTrue(today()->isSameDay($this->cart->paid_date));
});
