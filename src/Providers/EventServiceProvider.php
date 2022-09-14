<?php

namespace YiddisheKop\LaravelCommerce\Providers;

use YiddisheKop\LaravelCommerce\Events\CouponRedeemed;
use YiddisheKop\LaravelCommerce\Listeners\IncrementCouponTimesUsed;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CouponRedeemed::class => [
            IncrementCouponTimesUsed::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
