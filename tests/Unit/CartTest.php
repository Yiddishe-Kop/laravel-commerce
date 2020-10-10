<?php

use YiddisheKop\LaravelCommerce\Models\Order;

test('new cart is unpaid by default', function () {
  $this->assertEquals(1, Order::count());
  $this->assertEquals('cart', $this->cart->status);
  $this->assertEquals(false, $this->cart->is_paid);
});

it('can add items to the cart', function () {
  $this->cart->add($this->product);
  $this->assertEquals(1, $this->cart->items()->count());
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
  $this->cart->remove($this->product->id);
  $this->assertEquals(0, $this->cart->items()->count());
});
