<?php

namespace YiddisheKop\LaravelCommerce\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model {

  protected $guarded = [];

  public $timestamps = false;

  public $casts = [
    'purchase_data' => 'array'
  ];

  public function order() {
    return $this->belongsTo(Order::class);
  }

  public function model() {
    return $this->morphTo();
  }
}
