<?php

namespace YiddisheKop\LaravelCommerce;

use Illuminate\Support\Traits\ForwardsCalls;
use YiddisheKop\LaravelCommerce\Models\Order;
use YiddisheKop\LaravelCommerce\Traits\SessionCart;

class Cart {
  use SessionCart, ForwardsCalls;

  public function get(): Order {
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

  /**
   * Pass dynamic method calls to the Order.
   */
  public function __call($method, $arguments) {
    return $this->forwardCallTo($this->get(), $method, $arguments);
  }
}
