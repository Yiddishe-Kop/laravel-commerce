<?php

namespace  YiddisheKop\LaravelCommerce\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use YiddisheKop\LaravelCommerce\Contracts\Order;
use Illuminate\Broadcasting\InteractsWithSockets;

class OrderCompleted
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
