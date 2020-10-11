<?php

namespace YiddisheKop\LaravelCommerce;

use Illuminate\Support\Traits\ForwardsCalls;
use YiddisheKop\LaravelCommerce\Models\Order;
use YiddisheKop\LaravelCommerce\Traits\SessionCart;

class Cart {
  use SessionCart, ForwardsCalls;

  protected $user;

  public function __construct($user = null) {
    $this->user = auth()->id();
  }

  public function get(): Order {
    $this->user = auth()->id();
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

    if ($this->user && !$order->user_id) {
      $order->update([
        'user_id' => $this->user,
      ]);
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
