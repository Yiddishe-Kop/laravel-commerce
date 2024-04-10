<?php

namespace YiddisheKop\LaravelCommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use YiddisheKop\LaravelCommerce\Traits\HandlesCoupons;

class Coupon extends Model
{
    use HandlesCoupons;

    public const TYPE_PERCENTAGE = 'percentage';
    public const TYPE_FIXED = 'fixed';

    protected $guarded = [];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_to'   => 'datetime',
        'fixed_discount_currencies' => 'array',
    ];

    public function product(): MorphTo
    {
        return $this->morphTo();
    }
}
