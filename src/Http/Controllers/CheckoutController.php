<?php

namespace YiddisheKop\LaravelCommerce\Http\Controllers;

use Illuminate\Http\Request;
use YiddisheKop\LaravelCommerce\Facades\Cart;
use YiddisheKop\LaravelCommerce\Models\Order;

class CheckoutController extends Controller
{
    public function __invoke(Request $request, Order $order)
    {
        $order = Cart::find($order->id);
        /** @var \YiddisheKop\LaravelCommerce\Contracts\Gateway $gateway */
        $gateway = new $request->gateway();

        return $gateway->purchase($order, $request);
    }
}
