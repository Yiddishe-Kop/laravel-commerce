<?php

use YiddisheKop\LaravelCommerce\Models\Order;
use YiddisheKop\LaravelCommerce\Tests\Fixtures\Product;

test('new cart is unpaid by default', function () {
  $this->assertEquals(1, Order::withoutGlobalScope('complete')->count());
  $this->assertEquals('cart', $this->cart->status);
  $this->assertEquals(false, $this->cart->is_paid);
});

it('can add items to the cart', function () {
  $this->cart->add($this->product);
  $this->assertEquals(1, $this->cart->items()->count());
});

test('if same item is added again - it just updates the quantity', function() {
  $this->assertEquals(0, $this->cart->items()->count());
  $this->cart->add($this->product);
  $this->assertEquals(1, $this->cart->items()->count());
  $this->assertEquals(1, $this->cart->items()->first()->quantity);
  $this->cart->add($this->product);
  $this->assertEquals(1, $this->cart->items()->count());
  $this->assertEquals(2, $this->cart->items()->first()->quantity);
});

it('can update cart item quantity', function () {
  $this->cart->add($this->product);
  $cartItem = $this->cart->items->first();
  $this->assertEquals(1, $cartItem->quantity);
  $cartItem->update([
    'quantity' => 3
  ]);
  $this->assertEquals(3, $cartItem->quantity);
});

it('can remove items from the cart', function () {
  $this->cart->remove($this->product);
  $this->assertEquals(0, $this->cart->items()->count());
});

it('can empty the whole cart', function () {
  $this->cart->add($this->product, 3);
  $this->assertEquals(1, $this->cart->items()->count());
  $this->cart->add(Product::create([
    'title' => 'Hand Blender',
    'price' => 41
  ]));
  $this->assertEquals(2, $this->cart->items()->count());
  $this->cart->empty();
  $this->assertEquals(0, $this->cart->items()->count());
});

it('automatically removes deleted products from the cart', function() {
  $this->cart->add($this->product, 3);
  expect($this->cart->items()->get())->toHaveCount(1);
  $this->product->delete();
  $this->cart->calculateTotals();
  expect($this->cart->items()->get())->toHaveCount(0);
});
