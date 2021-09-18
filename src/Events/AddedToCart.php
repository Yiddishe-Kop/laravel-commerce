<?php

namespace  YiddisheKop\LaravelCommerce\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use YiddisheKop\LaravelCommerce\Contracts\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use YiddisheKop\LaravelCommerce\Contracts\Purchasable;

class AddedToCart
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Order $order;
    public Purchasable $product;

    public function __construct(Order $order, Purchasable $product)
    {
        $this->order = $order;
        $this->product = $product;
    }
}
