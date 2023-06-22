<?php

namespace YiddisheKop\LaravelCommerce\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use YiddisheKop\LaravelCommerce\Models\OrderItem;

class RemovedFromCart
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public OrderItem $orderItem,
    ) {
    }
}
