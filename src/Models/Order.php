<?php

namespace YiddisheKop\LaravelCommerce\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use YiddisheKop\LaravelCommerce\Traits\InteractsWithCartItems;

class Order extends Model {
  use HasFactory, InteractsWithCartItems;

  protected $guarded = [];
  protected $casts = [
    'is_paid' => 'boolean'
  ];
  public $timestamps = false;

  public function cartItems() {
    return $this->hasMany(OrderItem::class);
  }

}
