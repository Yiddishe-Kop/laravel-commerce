<?php

namespace YiddisheKop\LaravelCommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use YiddisheKop\LaravelCommerce\Traits\HandlesCoupons;

class Coupon extends Model
{
    use HandlesCoupons;

    const TYPE_PERCENTAGE = 'percentage';
    const TYPE_FIXED = 'fixed';

    protected $guarded = [];

    protected $dates = [
        'valid_from',
        'valid_to',
    ];

    public function product(): MorphTo
    {
        return $this->morphTo();
    }
}
