<?php

namespace YiddisheKop\LaravelCommerce\Listeners;

use YiddisheKop\LaravelCommerce\Models\Coupon;
use YiddisheKop\LaravelCommerce\Events\CouponRedeemed;

class IncrementCouponTimesUsed
{
    public function handle(CouponRedeemed $event)
    {
        Coupon::where('id', $event->coupon->id)->increment('times_used');
    }
}
