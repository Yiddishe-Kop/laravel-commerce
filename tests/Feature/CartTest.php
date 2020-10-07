<?php

namespace YiddisheKop\LaravelCommerce\Tests;

use YiddisheKop\LaravelCommerce\Models\Order;
use YiddisheKop\LaravelCommerce\Tests\Models\Product;

class CartTest extends TestCase {

  private $cart;
  private $product;

  public function setUp(): void {
    parent::setUp();

    Order::create();
    Product::create([
      'title' => 'BA Ziporen',
      'price' => 333
    ]);

    $this->cart = Order::first();
    $this->product = Product::first();
  }

  /** @test */
  public function new_cart_is_unpaid_by_default() {
    $this->assertEquals(1, Order::count());
    $this->assertEquals('cart', $this->cart->status);
    $this->assertEquals(false, $this->cart->is_paid);
  }

  /** @test */
  public function it_can_add_products_to_the_cart() {

    $this->cart->addItem($this->product);

    $this->assertEquals(1, $this->cart->cartItems()->count());
  }

  /** @test */
  public function it_can_update_cart_item_data() {

    $this->cart->addItem($this->product);
    $cartItem = $this->cart->cartItems->first();

    $this->assertEquals(1, $cartItem->quantity);

    $cartItem->update([
      'quantity' => 3
    ]);
    $this->assertEquals(3, $cartItem->quantity);
  }

  /** @test */
  public function it_can_remove_products_from_the_cart() {

    $this->cart->removeItem($this->product->id);

    $this->assertEquals(0, $this->cart->cartItems()->count());
  }
}
