<?php

namespace YiddisheKop\LaravelCommerce\Traits;

use YiddisheKop\LaravelCommerce\Facades\Cart;

trait Purchasable
{

    public function addToCart(int $quantity = 1, $options = null)
    {
        Cart::add($this, $quantity, $options);
    }

    public function removeFromCart()
    {
        Cart::remove($this);
    }

    public function getTitle(): string
    {
        return 'Untitled';
    }

    public function getPrice($currency = null, $options = null): int
    {
        return 0;
    }
}
