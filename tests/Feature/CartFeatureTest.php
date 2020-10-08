<?php

namespace YiddisheKop\LaravelCommerce\Tests;

use Orchestra\Testbench\Factories\UserFactory;
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
    $response = $this->actingAs($this->user)->post(route('cart.add'), [
      'product_type' => Product::class,
      'product_id' => $this->product->id,
    ]);

    $response->dumpSession();
    $response->dump();

    $response->assertSessionHas('cart');
  }
}
