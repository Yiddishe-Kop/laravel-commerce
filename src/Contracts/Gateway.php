<?php

namespace YiddisheKop\LaravelCommerce\Contracts;

interface Gateway {

  public function name(): string;

  public function prepare(array $data): array;

  public function purchase(array $data, $request): array;

  public function purchaseRules(): array;

}
