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
        if (! $this->isValidForOrder($order)) {
            throw new CouponNotFound('Coupon invalid for your products');
        }
        $order->update([
            'coupon_id' => $this->id,
        ]);

        return true;
    }

    public function isValidForOrder(Order $order): bool
    {
        if (! $this->isLimitedToProduct()) {
            return true;
        }

        return $order->items()
            ->where('model_type', $this->product_type)
            ->where('model_id', $this->product_id)
            ->exists();
    }

    /**
     * Calculate the amount to discount the Order
     */
    public function calculateDiscount($originalPrice, $currency = null)
    {

        if (! $this->isValid()) {
            return 0;
        }
        if (! is_null($this->max_uses) && $this->times_used >= $this->max_uses) {
            return 0;
        }

        

        if ($this->type == Coupon::TYPE_FIXED) {
            // TODO: Better way of handling potential enum?
            if (!is_string($currency) && $currency?->value) {
                $currency = $currency->value;
            }
            $discount = data_get($this->fixed_discount_currencies, $currency, $this->discount);

        } elseif ($this->type == Coupon::TYPE_PERCENTAGE) {
            $discount = ($originalPrice / 100) * $this->discount;
        }

        return $discount;
    }
}
