<?php

namespace YiddisheKop\LaravelCommerce\Traits;

use YiddisheKop\LaravelCommerce\Models\Order;

trait HasOrders {

  public function orders() {
    return $this->hasMany(Order::class)->where('status', Order::STATUS_COMPLETED);
  }

}
