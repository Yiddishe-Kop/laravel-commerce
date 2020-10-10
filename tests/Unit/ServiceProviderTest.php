<?php

use YiddisheKop\LaravelCommerce\Cart;

test('the Cart is bound to the container', function () {
  $this->assertInstanceOf(Cart::class, $this->app['cart']);
});
