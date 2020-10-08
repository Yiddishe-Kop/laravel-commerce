<?php

namespace YiddisheKop\LaravelCommerce;

use YiddisheKop\LaravelCommerce\Models\Order;
use YiddisheKop\LaravelCommerce\Traits\SessionCart;

class Cart {
  use SessionCart;

  public function get() {
    return $this->getOrMakeSessionCart();
  }

  public function find($id) {
    return Order
      ::isCart()
      ->findOrFail($id);
  }

  public function create() {
    return Order::create();
  }
}
