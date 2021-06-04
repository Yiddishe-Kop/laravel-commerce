<?php

namespace YiddisheKop\LaravelCommerce\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{

    public function __invoke(Request $request)
    {

        Log::info('Payment webhook recieved:');
        Log::info($request->input());

        $gatewayClass = 'App\\Gateways\\' . $request->gateway;
        /** @var \YiddisheKop\LaravelCommerce\Contracts\Gateway $gateway */
        $gateway = new $gatewayClass();

        return $gateway->webhook($request);
    }
}
