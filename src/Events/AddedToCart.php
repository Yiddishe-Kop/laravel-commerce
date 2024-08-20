<?php

namespace  YiddisheKop\LaravelCommerce\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use YiddisheKop\LaravelCommerce\Contracts\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use YiddisheKop\LaravelCommerce\Contracts\Purchasable;
use YiddisheKop\LaravelCommerce\Models\OrderItem;

class AddedToCart
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public function __construct(
        public Order $order,
        public Purchasable $product,
        public OrderItem $orderItem,
    ) {
    }
}
