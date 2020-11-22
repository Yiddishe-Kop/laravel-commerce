<?php

namespace YiddisheKop\LaravelCommerce\Traits;

use App\Events\OrderCompleted;
use YiddisheKop\LaravelCommerce\Exceptions\OrderAlreadyComplete;
use YiddisheKop\LaravelCommerce\Exceptions\OrderNotAssignedToUser;
use YiddisheKop\LaravelCommerce\Models\Order;

trait HandlesOrders {

  public function setCurrency(string $currency) {

    if ($this->status == Order::STATUS_COMPLETED) {
      throw new OrderAlreadyComplete("Can't change the currency after order has been completed", 1);
    }

    $this->update([
      'currency' => $currency
    ]);
    $this->calculateTotals();
    return $this;
  }

  public function markAsCompleted(): self {

    if (!$this->user_id) {
      throw new OrderNotAssignedToUser("No user assigned to order", 1);
    }

    $this->update([
      'paid_at' => now(),
      'status' => Order::STATUS_COMPLETED,
    ]);

    event(new OrderCompleted($this));

    return $this;
  }
}
