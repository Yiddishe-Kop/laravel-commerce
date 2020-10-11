<?php

namespace YiddisheKop\LaravelCommerce\Gateways;

use YiddisheKop\LaravelCommerce\Contracts\Gateway;

class Example implements Gateway {

  public function name(): string {
    return 'Credit Card';
  }

  public function prepare(array $data): array {
    // prepare the credit-card form
    return [];
  }

  public function purchase(array $data, $request) {
    // perform the purchase
    return 'Purchased with Credit Card!';
  }

  public function purchaseRules(): array {
    return  [
      'payment_method' => 'required|string'
    ];
  }

}
