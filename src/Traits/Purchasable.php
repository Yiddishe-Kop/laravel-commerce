<?php

namespace YiddisheKop\LaravelCommerce\Traits;

use YiddisheKop\LaravelCommerce\Models\OrderItem;

/**
 * Can be purchased
 */
trait Purchasable {

  public function cartItems() {
    return $this->morphMany(OrderItem::class, 'model');
  }

}
