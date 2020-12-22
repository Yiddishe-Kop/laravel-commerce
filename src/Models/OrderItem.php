<?php

namespace YiddisheKop\LaravelCommerce\Models;

use Illuminate\Database\Eloquent\Model;
use YiddisheKop\LaravelCommerce\Casts\Money;

class OrderItem extends Model {

  public $timestamps = false;

  protected $guarded = [];

  protected $casts = [
    'price', Money::class,
    'options' => 'array'
  ];

  public function order() {
    return $this->belongsTo(Order::class);
  }

  public function model() {
    return $this->morphTo();
  }
}
