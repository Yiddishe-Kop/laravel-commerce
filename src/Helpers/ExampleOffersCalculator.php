<?php

namespace YiddisheKop\LaravelCommerce\Helpers;

use YiddisheKop\LaravelCommerce\Contracts\Order;

class ExampleOffersCalculator
{
    public static function apply(Order $order)
    {
        $orderItem = $order->items->first();
        if (!$orderItem) return;
        $orderItem->discount = $orderItem->price / 2; // 50% discount [* qty]
        $orderItem->save();
    }
}
