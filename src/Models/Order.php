<?php

namespace YiddisheKop\LaravelCommerce\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use YiddisheKop\LaravelCommerce\Traits\HandlesOrders;
use YiddisheKop\LaravelCommerce\Traits\HandlesCartItems;

class Order extends Model {
  use HasFactory, HandlesCartItems, HandlesOrders;

  const STATUS_CART = 'cart';
  const STATUS_COMPLETED = 'completed';

  protected $guarded = [];

  protected $dates = [
    'paid_date'
  ];

  protected $casts = [
    'is_paid' => 'boolean',
  ];

  public $timestamps = false;

  public function orderItems() {
    return $this->hasMany(OrderItem::class);
  }

}
