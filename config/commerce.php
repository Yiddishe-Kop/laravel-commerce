<?php

use YiddisheKop\LaravelCommerce\Gateways\PayPal;

return [

  // default currency
  'currency' => 'USD',

  // default tax rate
  'tax' => [
    'rate'               => 20,
    'included_in_prices' => false,
  ],

  // default shipping amount
  'shipping' => [
    'cost' => 12
  ],

  /*
  |--------------------------------------------------------------------------
  | Payment Gateways
  |--------------------------------------------------------------------------
  |
  | You can setup multiple payment gateways for your store.
  | Here's where you can configure the gateways in use.
  */
  'gateways' => [
    PayPal::class => [],
  ],

  'prefix' => 'commerce', // routes prefix
  'middleware' => ['web'], // you probably want to include 'web' here

];
