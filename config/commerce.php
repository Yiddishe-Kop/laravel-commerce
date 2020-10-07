<?php

use YiddisheKop\LaravelCommerce\Gateways\PayPal;
use YiddisheKop\LaravelCommerce\Gateways\Takbull;

return [

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

];
