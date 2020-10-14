<?php

namespace YiddisheKop\LaravelCommerce\Contracts;

interface Purchasable {

  public function getTitle(): string;

  public function getPrice($currency = null): int;

}
