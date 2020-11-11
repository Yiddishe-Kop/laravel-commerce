<?php

namespace YiddisheKop\LaravelCommerce\Traits;

use Illuminate\Support\Facades\Session;
use YiddisheKop\LaravelCommerce\Facades\Cart;
use YiddisheKop\LaravelCommerce\Models\Order;

trait SessionCart {

  protected function getSessionCartKey(): string {
    return Session::get('cart');
  }

  protected function getSessionCart() {

    $cart = Cart::find($this->getSessionCartKey());

    // attach to user if logged in
    if ($this->user && !$cart->user_id) {
      $cart->update([
        'user_id' => $this->user,
      ]);
    }

    return $cart;
  }

  public function hasSessionCart(): bool {
    return Session::has('cart');
  }

  protected function makeSessionCart(): Order {

    $cart = Cart::create([
      'user_id' => $this->user,
      'currency' => config('commerce.currency'),
    ]);

    Session::put('cart', $cart->id);

    return $cart;
  }

  protected function getOrMakeSessionCart(): Order {
    if ($this->hasSessionCart()) {
      return $this->getSessionCart();
    }

    return $this->makeSessionCart();
  }

  protected function forgetSessionCart() {
    Session::forget('cart');
  }

  protected function refreshSessionCart(): Order {
    $this->forgetSessionCart();
    return $this->makeSessionCart();
  }
}
