<?php

namespace YiddisheKop\LaravelCommerce\Helpers;

use YiddisheKop\LaravelCommerce\Contracts\Order;

class ExampleShippingCalculator
{
    public static function calculate(Order $order)
    {
        return 24 * 100;
    }
}
