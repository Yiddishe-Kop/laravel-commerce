<?php

namespace YiddisheKop\LaravelCommerce\Traits;

use YiddisheKop\LaravelCommerce\Facades\Cart;
use YiddisheKop\LaravelCommerce\Models\Order;
use YiddisheKop\LaravelCommerce\Models\OrderItem;

trait Purchasable {

  public static function bootPurchasable()
  {
      static::deleted(function ($product) {
          $product->orderItems->load('order')->each(function ($item) {
              if ($item->order->status == Order::STATUS_CART) $item->delete();
          });
      });
  }

    public function orderItems()
    {
        return $this->morphMany(OrderItem::class, 'model');
    }

  public function addToCart(int $quantity = 1) {
    Cart::add($this, $quantity);
  }

  public function removeFromCart() {
    Cart::remove($this);
  }

  public function getTitle(): string {
    return 'Untitled';
  }

  public function getPrice($currency = null): int {
    return 0;
  }
}
