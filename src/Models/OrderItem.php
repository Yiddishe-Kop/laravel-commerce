<?php

namespace YiddisheKop\LaravelCommerce\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model {

  protected $guarded = [];

  public $timestamps = false;

  public function order() {
    return $this->belongsTo(Order::class);
  }

  public function model() {
    return $this->morphTo();
  }
}
