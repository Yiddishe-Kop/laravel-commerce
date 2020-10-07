<?php

namespace YiddisheKop\LaravelCommerce\Traits;

use YiddisheKop\LaravelCommerce\Models\OrderItem;

/**
 * Can be purchased
 */
trait InteractsWithCartItems {

  public function removeItem(int $id) {
    return OrderItem::where('model_id', $id)->delete();
  }

}
