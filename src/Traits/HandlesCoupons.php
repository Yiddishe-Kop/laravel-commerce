<?php

namespace YiddisheKop\LaravelCommerce\Traits;

use YiddisheKop\LaravelCommerce\Models\Coupon;
use YiddisheKop\LaravelCommerce\Contracts\Order;
use YiddisheKop\LaravelCommerce\Exceptions\CouponExpired;
use YiddisheKop\LaravelCommerce\Exceptions\CouponNotFound;
use YiddisheKop\LaravelCommerce\Exceptions\CouponLimitReached;

/**
 * Coupon methods
 */
trait HandlesCoupons
{
    /**
     * Check if the coupon is limited to a specific product
     */
    public function isLimitedToProduct(): bool
    {
        return ! is_null($this->product_type) && ! is_null($this->product_id);
    }

    /**
     * Check if the coupon is valid
     */
    public function isValid()
    {
        if (($this->valid_from && $this->valid_from > now())
            || ($this->valid_to && $this->valid_to < now())
        ) {
            return false;
        }

        return true;
    }

    /**
     * Usage limit reached
     */
    public function usageLimitReached(): bool
    {
        if (! is_null($this->max_uses) && $this->times_used >= $this->max_uses) {
            return true;
        }

        return false;
    }

    /**
     * Apply the coupon to an Order
     */
    public function apply(Order $order)
    {
        if (! $this->isValid()) {
            throw new CouponExpired('The coupon is no longer valid', 1);
        }
        if ($this->usageLimitReached()) {
            throw new CouponLimitReached("The coupon has been used to it's max", 1);
        }
        if ($this->isLimitedToProduct() && ($order->items()->where('model_type', $this->product_type)->where('model_id', $this->product_id))->count() == 0) {
            throw new CouponNotFound('Coupon invalid for your products');
        }
        $order->update([
            'coupon_id' => $this->id,
        ]);

        return true;
    }

    /**
     * Calculate the amount to discount the Order
     */
    public function calculateDiscount($originalPrice)
    {
        if (! $this->isValid()) {
            return 0;
        }
        if (! is_null($this->max_uses) && $this->times_used >= $this->max_uses) {
            return 0;
        }
        if ($this->type == Coupon::TYPE_FIXED) {
            $discount = $this->discount;
        } elseif ($this->type == Coupon::TYPE_PERCENTAGE) {
            $discount = ($originalPrice / 100) * $this->discount;
        }

        return $discount;
    }
}
