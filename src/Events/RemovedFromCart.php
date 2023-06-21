<?php

namespace YiddisheKop\LaravelCommerce\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use YiddisheKop\LaravelCommerce\Models\OrderItem;

class RemovedFromCart
{
    use Dispatchable;
    use SerializesModels;

    public OrderItem $orderItem;

    public function __construct(OrderItem $orderItem)
    {
        $this->orderItem = $orderItem;
    }
}
