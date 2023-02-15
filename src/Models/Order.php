<?php

namespace YiddisheKop\LaravelCommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use YiddisheKop\LaravelCommerce\Traits\HandlesOrders;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use YiddisheKop\LaravelCommerce\Traits\HandlesCartItems;
use YiddisheKop\LaravelCommerce\Contracts\Order as OrderContract;

class Order extends Model implements OrderContract
{
    use HasFactory;
    use HandlesCartItems;
    use HandlesOrders;

    public const STATUS_CART = 'cart';
    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';

    protected $guarded = [];

    protected $casts = [
        'paid_at'      => 'datetime',
        'gateway_data' => 'array',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('commerce.models.user', 'App\\Models\\User'));
    }

    public function scopeIsCart($query)
    {
        return $query->where('status', self::STATUS_CART);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (self $cart) {
            return $cart->items()->delete();
        });
    }
}
