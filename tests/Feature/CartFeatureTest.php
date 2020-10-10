<?php

use Orchestra\Testbench\Factories\UserFactory;
use YiddisheKop\LaravelCommerce\Cart;
use YiddisheKop\LaravelCommerce\Tests\Fixtures\Product;

beforeEach(function () {
  $this->user = UserFactory::new()->make([
    'name' => 'Yehuda',
    'email' => 'yehuda@yiddishe-kop.com',
  ]);
});

test('a user can add items to his cart', function () {
  $response = $this
    ->actingAs($this->user)
    ->post(route('cart.add'), [
      'product_type' => Product::class,
      'product_id' => $this->product->id,
      'quantity' => 2,
    ]);

  $cart = (new Cart)->get();

  $cartItem = $cart->items->first();
  $this->assertEquals(2, $cartItem->quantity);

  $this->product->addToCart(44);
  $cartItem = $cart->items()->where('quantity', '>', 5)->first();
  $this->assertEquals(44, $cartItem->quantity);

  $this->assertEquals($cart->id, session('cart'));

  // $response->dumpSession();
  // $response->dump();

  $response->assertSessionHas('cart');
});

/** @test */
test('a guest can add items to his cart', function () {
  $response = $this
    ->post(route('cart.add'), [
      'product_type' => Product::class,
      'product_id' => $this->product->id,
      'quantity' => 3,
    ]);

  $cart = (new Cart)->get();

  $cartItem = $cart->items->first();
  $this->assertEquals(3, $cartItem->quantity);

  $this->product->addToCart(33);
  $cartItem = $cart->items()->where('quantity', '>', 5)->first();
  $this->assertEquals(33, $cartItem->quantity);

  $this->assertEquals($cart->id, session('cart'));

  $response->assertSessionHas('cart');
});
