<?php

namespace YiddisheKop\LaravelCommerce\Traits;

use Illuminate\Support\Facades\Session;
use YiddisheKop\LaravelCommerce\Facades\Cart;

trait SessionCart {

  protected function getSessionCartKey(): string {
    return Session::get('cart');
  }

  protected function getSessionCart() {
    return Cart::find($this->getSessionCartKey());
  }

  protected function hasSessionCart(): bool {
    return Session::has('cart');
  }

  protected function makeSessionCart() {
    $cart = Cart::create();

    Session::put('cart', $cart->id);

    return $cart;
  }

  protected function getOrMakeSessionCart() {
    if ($this->hasSessionCart()) {
      return $this->getSessionCart();
    }

    return $this->makeSessionCart();
  }

  protected function forgetSessionCart() {
    Session::forget('cart');
  }
}
