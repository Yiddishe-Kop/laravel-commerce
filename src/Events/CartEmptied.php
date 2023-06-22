<?php

namespace YiddisheKop\LaravelCommerce\Events;

use Illuminate\Queue\SerializesModels;
use YiddisheKop\LaravelCommerce\Models\Order;
use Illuminate\Foundation\Events\Dispatchable;

class CartEmptied
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public Order $order,
    ) {
    }
}
