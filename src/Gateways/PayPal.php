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

  public function purchase(array $data, $request): array {
    // perform the purchase
    return [];
  }

  public function purchaseRules(): array {
    return  [
      'payment_method' => 'required|string'
    ];
  }

}
