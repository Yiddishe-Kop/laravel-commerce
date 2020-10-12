<?php

namespace YiddisheKop\LaravelCommerce\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use YiddisheKop\LaravelCommerce\Traits\HandlesOrders;
use YiddisheKop\LaravelCommerce\Traits\HandlesCartItems;

class Order extends Model {
  use HasFactory, HandlesCartItems, HandlesOrders;

  const STATUS_CART = 'cart';
  const STATUS_PENDING = 'pending';
  const STATUS_COMPLETED = 'completed';

  protected $guarded = [];

  protected $dates = [
    'paid_date'
  ];

  protected $casts = [
    'is_paid' => 'boolean',
  ];

  public function items() {
    return $this->hasMany(OrderItem::class);
  }

  public function scopeIsCart($query) {
    return $query->where('status', self::STATUS_CART);
  }

  public function scopeCompleted($query) {
    return $query->where('status', self::STATUS_COMPLETED);
  }

  public function empty() {
    $this->items()->delete();
  }

  protected static function boot() {
    parent::boot();

    static::deleting(function (self $cart) {
      return $cart->items()->delete();
    });
  }

  protected static function booted() {
    static::addGlobalScope('complete', function (Builder $builder) {
      $builder->where('status', self::STATUS_COMPLETED);
    });
  }

}
