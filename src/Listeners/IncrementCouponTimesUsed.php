<?php

namespace YiddisheKop\LaravelCommerce\Listeners;

use YiddisheKop\LaravelCommerce\Events\CouponRedeemed;
use YiddisheKop\LaravelCommerce\Models\Coupon;

class IncrementCouponTimesUsed
{

    public function handle(CouponRedeemed $event)
    {
        Coupon::where('id', $event->coupon->id)->increment('times_used');
    }
}
