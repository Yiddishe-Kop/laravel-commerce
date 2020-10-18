<?php

namespace YiddisheKop\LaravelCommerce\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use YiddisheKop\LaravelCommerce\Contracts\Purchasable as PurchasableContract;
use YiddisheKop\LaravelCommerce\Traits\Purchasable;

class Product extends Model implements PurchasableContract {
  use Purchasable;

  protected $guarded = [];

  public function getTitle(): string {
    return $this->title;
  }

  public function getPrice($currency = null): int {
    // this is just for testing
    return $this->price * ($currency == 'GBP' ? 0.5 : 1);
  }

}
