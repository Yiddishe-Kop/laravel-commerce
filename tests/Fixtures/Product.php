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

  public function getPrice(): int {
    return $this->price;
  }

}
