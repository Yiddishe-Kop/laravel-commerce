<?php

namespace YiddisheKop\LaravelCommerce\Tests;

use Illuminate\Support\Facades\Session;
use Orchestra\Testbench\Factories\UserFactory;
use YiddisheKop\LaravelCommerce\Cart;
use YiddisheKop\LaravelCommerce\Tests\Fixtures\Product;

class CartFeatureTest extends CommerceTestCase {

  private $user;

  public function setUp(): void {
    parent::setUp();

    $this->user = UserFactory::new()->make([
      'name' => 'Yehuda',
      'email' => 'yehuda@yiddishe-kop.com',
    ]);
  }

  /** @test */
  public function user_can_add_items_to_cart() {
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

    $response->dumpSession();
    $response->dump();

    $response->assertSessionHas('cart');
  }

  /** @test */
  public function different_user_gets_different_cart() {
    $this->actingAs($this->user)
      ->post(route('cart.add'), [
        'product_type' => Product::class,
        'product_id' => $this->product->id,
        'quantity' => 11,
      ]);
    $cart1 = (new Cart)->get();

    dump($cart1->items()->count());

    $response = $this
      ->actingAs(UserFactory::new()->make([
        'name' => 'Aryeh',
        'email' => 'aryeh@yiddishe-kop.com',
      ]))
      ->post(route('cart.add'), [
        'product_type' => Product::class,
        'product_id' => $this->product->id,
        'quantity' => 22,
      ]);

    $cart2 = (new Cart)->get();
    dump($cart2->items()->count());

    $this->assertNotEquals($cart1->id, $cart2->id);

    $response->assertSessionHas('cart');
  }

  /** @test */
  public function guest_can_add_items_to_cart() {
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

    $response->dumpSession();
    $response->dump();

    $response->assertSessionHas('cart');
  }
}
