<?php

namespace YiddisheKop\LaravelCommerce\Gateways;

use Illuminate\Http\Request;
use YiddisheKop\LaravelCommerce\Contracts\Gateway;
use YiddisheKop\LaravelCommerce\Contracts\Order;

class Example implements Gateway
{

    public static function name(): string
    {
        return 'Credit Card (demo)';
    }

    public function purchase(Order $order, Request $request)
    {
        echo 'redirecting user to payment page...';
    }

    public function complete(Order $order, Request $request)
    {
        echo 'marking order as complete...';
    }

    public function webhook(Request $request)
    {
        echo 'processing webhook...';
    }
}
