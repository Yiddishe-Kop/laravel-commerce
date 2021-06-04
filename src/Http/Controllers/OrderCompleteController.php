<?php

namespace YiddisheKop\LaravelCommerce\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use YiddisheKop\LaravelCommerce\Models\Order;

class OrderCompleteController extends Controller
{

    public function __invoke(Order $order, Request $request)
    {

        Log::info('Completing payment: ' . $order->gateway);
        Log::info($request->input());

        // complete the payment
        /** @var \YiddisheKop\LaravelCommerce\Contracts\Gateway $gateway */
        $gateway = new $order->gateway();
        return $gateway->complete($order, $request);
    }
}
