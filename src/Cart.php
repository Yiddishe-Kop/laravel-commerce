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

  public function find($id): Order {
    $order = Order
      ::isCart()
      ->with('items')
      ->find($id);

    if (!$order) {
      return $this->refreshSessionCart();
    }

    return $order;
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
