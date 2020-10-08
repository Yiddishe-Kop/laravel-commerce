<?php

use YiddisheKop\LaravelCommerce\Gateways\PayPal;
use YiddisheKop\LaravelCommerce\Gateways\Takbull;

return [

  'currency' => 'USD',

  'tax' => [
    'rate'               => 20,
    'included_in_prices' => false,
  ],

  /*
  |--------------------------------------------------------------------------
  | Payment Gateways
  |--------------------------------------------------------------------------
  |
  | You can setup multiple payment gateways for your store with Simple Commerce.
  | Here's where you can configure the gateways in use.
  */
  'gateways' => [
    Takbull::class => [],
    PayPal::class => [],
  ],

  'prefix' => 'commerce',
  'middleware' => ['web'], // you probably want to include 'web' here

];
