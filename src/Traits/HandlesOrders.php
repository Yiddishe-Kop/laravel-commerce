<?php

namespace YiddisheKop\LaravelCommerce\Traits;

use YiddisheKop\LaravelCommerce\Exceptions\OrderNotAssignedToUser;
use YiddisheKop\LaravelCommerce\Models\Order;

trait HandlesOrders {

  public function markAsCompleted(): self {

    if (!$this->user_id) {
      throw new OrderNotAssignedToUser("No user assigned to order", 1);
    }

    $this->update([
      'is_paid' => true,
      'paid_date' => now(),
      'status' => Order::STATUS_COMPLETED,
    ]);

    // send email confirmation to customer...

    return $this;
  }
}
