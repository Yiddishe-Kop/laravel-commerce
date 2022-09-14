<?php

namespace  YiddisheKop\LaravelCommerce\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use YiddisheKop\LaravelCommerce\Models\Coupon;
use Illuminate\Broadcasting\InteractsWithSockets;

class CouponRedeemed
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $coupon;

    public function __construct(Coupon $coupon)
    {
        $this->coupon = $coupon;
    }
}
