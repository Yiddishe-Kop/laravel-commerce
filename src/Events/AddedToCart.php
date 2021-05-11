<?php

namespace  YiddisheKop\LaravelCommerce\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use YiddisheKop\LaravelCommerce\Contracts\Purchasable;
use YiddisheKop\LaravelCommerce\Models\Order;

class AddedToCart {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $product;

    public function __construct(Order $order, Purchasable $product) {
        $this->order = $order;
        $this->product = $product;
    }
}
