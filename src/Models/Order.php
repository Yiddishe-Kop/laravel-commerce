<?php

namespace YiddisheKop\LaravelCommerce\Models;

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
        'paid_at'
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'gateway_data' => 'array',
    ];

    protected $appends = [
        'timeAgo',
    ];

    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    public function coupon() {
        return $this->belongsTo(Coupon::class);
    }

    public function user() {
        return $this->belongsTo(config('user', 'App\\User'));
    }

    public function getTimeAgoAttribute() {
        if (!$this->paid_at) return null;
        return $this->paid_at->diffForHumans();
    }

    public function scopeIsCart($query) {
        return $query->where('status', self::STATUS_CART);
    }

    public function scopeCompleted($query) {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function (self $cart) {
            return $cart->items()->delete();
        });
    }
}
