<?php

namespace YiddisheKop\LaravelCommerce\Contracts;

use Illuminate\Http\Request;
use YiddisheKop\LaravelCommerce\Models\Order;

interface Gateway {

  public function name(): string;

  public function purchase(Order $order, Request $request);

  public function complete(Order $order, Request $request);

  public function webhook(Request $request);

}
