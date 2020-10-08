<?php

namespace YiddisheKop\LaravelCommerce\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use YiddisheKop\LaravelCommerce\Contracts\Purchasable;

class Product extends Model implements Purchasable {

  protected $guarded = [];

  public function getTitle(): string {
    return $this->title;
  }

  public function getPrice(): int {
    return $this->price;
  }

}
