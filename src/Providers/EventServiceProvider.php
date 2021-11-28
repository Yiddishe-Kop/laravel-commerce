<?php

namespace YiddisheKop\LaravelCommerce\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use YiddisheKop\LaravelCommerce\Events\CouponRedeemed;
use YiddisheKop\LaravelCommerce\Listeners\IncrementCouponTimesUsed;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        CouponRedeemed::class => [
            IncrementCouponTimesUsed::class,
        ]
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
