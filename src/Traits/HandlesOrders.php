<?php

namespace YiddisheKop\LaravelCommerce\Traits;

use YiddisheKop\LaravelCommerce\Models\Order;

trait HandlesOrders {

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
