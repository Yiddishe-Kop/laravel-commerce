<?php

namespace YiddisheKop\LaravelCommerce\Tests\Fixtures;

use YiddisheKop\LaravelCommerce\Models\Order;
use YiddisheKop\LaravelCommerce\Contracts\Order as OrderContract;

class MyOrder extends Order implements OrderContract
{
    protected $table = 'orders';

    public function calculateTotals(): self
    {
        // Overriden method: calculateTotals - everthing is free!
        $this->update([
            'items_total' => 0,
            'coupon_total' => 0,
            'tax_total' => 0,
            'shipping_total' => 0,
            'grand_total' => 0,
        ]);

        return $this;
    }
}
