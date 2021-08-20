<?php

use YiddisheKop\LaravelCommerce\Gateways\Example;
use YiddisheKop\LaravelCommerce\Helpers\ExampleShippingCalculator;

return [

    // default currency
    'currency' => 'USD',

    // default tax rate
    'tax' => [
        'rate'               => 0.2,
        'included_in_prices' => false,
    ],

    // Coupon settings
    'coupon' => [
        'include_tax' => true, // if to apply the coupon after taxes
        'include_shipping' => true, // if to apply the coupon after shipping
    ],

    // default shipping amount
    'shipping' => [
        'calculator' => ExampleShippingCalculator::class, // if you need to calculate shipping depending on the order, use a class that implements a `calculate(Order $order)` method
        'cost' => 12, // if calculator is null, this will be used
    ],

    // your user model - replace this with your user model
    'user' => 'App\\User',

    /*
  |--------------------------------------------------------------------------
  | Payment Gateways
  |--------------------------------------------------------------------------
  |
  | You can setup multiple payment gateways for your store.
  | Here's where you can configure the gateways in use.
  */
    'gateways' => [
        Example::class => [], // demo gateway
    ],

    'prefix' => 'commerce', // routes prefix
    'middleware' => ['web'], // you probably want to include 'web' here

];
