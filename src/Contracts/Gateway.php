<?php

namespace YiddisheKop\LaravelCommerce\Contracts;

use Illuminate\Http\Request;
use YiddisheKop\LaravelCommerce\Models\Order;

interface Gateway {

  /**
   *  The name of the payment gateway
   */
  public static function name(): string;

  /**
   *  Start the payment flow
   */
  public function purchase(Order $order, Request $request);

  /**
   *  Complete the purchase
   */
  public function complete(Order $order, Request $request);

  /**
   *  Payment Success notification
   *
   *  Some providers use this to verify payment status
   */
  public function webhook(Request $request);

}
