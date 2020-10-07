<?php

namespace YiddisheKop\LaravelCommerce\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use YiddisheKop\LaravelCommerce\Models\Order;

class OrderFactory extends Factory {
  protected $model = Order::class;
  public function definition() {
    return [];
  }
}
