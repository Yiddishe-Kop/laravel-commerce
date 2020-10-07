<?php

namespace YiddisheKop\LaravelCommerce\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use YiddisheKop\LaravelCommerce\Traits\Purchasable;

class Product extends Model {
  use Purchasable;
  protected $guarded = [];

}
