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

  public function items() {
    return $this->hasMany(OrderItem::class);
  }

  public function scopeIsCart($query) {
    return $query->where('status', self::STATUS_CART);
  }

  protected static function boot() {
    parent::boot();

    static::deleting(function (self $cart) {
      return $cart->items()->delete();
    });
  }
}
