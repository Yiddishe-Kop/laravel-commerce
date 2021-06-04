<?php

namespace  YiddisheKop\LaravelCommerce\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use YiddisheKop\LaravelCommerce\Models\Coupon;

class CouponRedeemed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $coupon;

    public function __construct(Coupon $coupon)
    {
        $this->coupon = $coupon;
    }
}
