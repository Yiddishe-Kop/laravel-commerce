<?php

namespace YiddisheKop\LaravelCommerce\Traits;

use YiddisheKop\LaravelCommerce\Models\Order;
use YiddisheKop\LaravelCommerce\Models\OrderItem;

trait HasOrders
{

    public function orders()
    {
        return $this->hasMany(config('commerce.models.order', Order::class))
            ->where('status', Order::STATUS_COMPLETED);
    }

    public function orderItems()
    {
        return $this->hasManyThrough(OrderItem::class, config('commerce.models.order', Order::class))
            ->where('orders.status', Order::STATUS_COMPLETED);
    }
}
