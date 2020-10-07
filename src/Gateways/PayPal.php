<?php

namespace YiddisheKop\LaravelCommerce\Gateways;

use YiddisheKop\LaravelCommerce\Contracts\Gateway;

class PayPal implements Gateway {

  public function name(): string {
    return 'PayPal';
  }

  public function prepare(array $data): array {
    // prepare the credit-card form
    return [];
  }

  public function purchase(array $data, $request) {
    // perform the purchase
    return 'Purchased with PayPal!';
  }

  public function purchaseRules(): array {
    return  [
      'payment_method' => 'required|string'
    ];
  }

}
