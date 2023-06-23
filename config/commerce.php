<?php

use YiddisheKop\LaravelCommerce\Gateways\Example;
use YiddisheKop\LaravelCommerce\Helpers\ExampleOffersCalculator;
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
        'include_tax'      => true, // if to apply the coupon after taxes
        'include_shipping' => true, // if to apply the coupon after shipping
    ],

    /*
    |--------------------------------------------------------------------------
    | Shipping
    |--------------------------------------------------------------------------
    |
    | You can set a fixed shipping amount. If you need to calculate the cost
    | according to each order, just pass a class that implements a
    | `calculate(Order $order)` method.
    */
    'shipping' => [
        'calculator' => ExampleShippingCalculator::class,
        'cost'       => 12, // if calculator is null, this will be used
    ],

    /*
    |--------------------------------------------------------------------------
    | Offers Calculator
    |--------------------------------------------------------------------------
    |
    | You can apply discounts to order_items by creating a class that implements
    | an `apply(Order $order)` method. This method will get the `Order`
    | passed to it as a parameter. You should apply offers by setting
    | the `discount` on order_items.
    */
    'offers' => [
        'calculator' => ExampleOffersCalculator::class,
    ],

    'models' => [
        // the order model - you can replace this with your own Order model that extends this class & implements the Order contract
        'order'     => YiddisheKop\LaravelCommerce\Models\Order::class,
        'orderItem' => YiddisheKop\LaravelCommerce\Models\OrderItem::class,
        // your user model - replace this with your user model
        'user' => 'App\\Models\\User',
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
        Example::class => [], // demo gateway
    ],

    'prefix'     => 'commerce', // routes prefix
    'middleware' => ['web'], // you probably want to include 'web' here

];
