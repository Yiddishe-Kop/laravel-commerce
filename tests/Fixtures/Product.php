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

  public function getPrice($currency = null, $options = null): int {
    // this is just for testing
    $price = $this->price * 100;
    if ($options) {
      $price += [
        'small' => 1000,
        'medium' => 2000,
        'large' => 3000,
        ][$options['size']];
      }

    if ($currency == 'GBP') {
      return $price / 2; // example to demonstrate how to handle currencies
    }

    return $price;
  }

}
