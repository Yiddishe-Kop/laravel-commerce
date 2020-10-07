<?php

namespace YiddisheKop\LaravelCommerce\Traits;

use YiddisheKop\LaravelCommerce\Models\Order;
use YiddisheKop\LaravelCommerce\Models\OrderItem;

trait HandlesOrders {

  public function orderItems() {
    return $this->hasMany(OrderItem::class);
  }

  public function markAsCompleted(): self {
    $this->update([
      'is_paid' => true,
      'paid_date' => now(),
      'status' => Order::STATUS_COMPLETED,
    ]);

    // send email confirmation to customer...

    return $this;
  }
}
