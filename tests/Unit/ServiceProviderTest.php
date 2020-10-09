<?php

namespace YiddisheKop\LaravelCommerce\Tests;

use YiddisheKop\LaravelCommerce\Cart;

class ServiceProviderTest extends TestCase {

  /** @test */
  public function it_binds_the_cart_to_the_service_container() {
    $this->assertInstanceOf(Cart::class, $this->app['cart']);
  }

}
