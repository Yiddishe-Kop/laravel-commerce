<?php

namespace YiddisheKop\LaravelCommerce\Traits;

use YiddisheKop\LaravelCommerce\Contracts\Purchasable;
use YiddisheKop\LaravelCommerce\Events\AddedToCart;
use YiddisheKop\LaravelCommerce\Exceptions\CouponNotFound;
use YiddisheKop\LaravelCommerce\Helpers\Vat;
use YiddisheKop\LaravelCommerce\Models\Coupon;
use YiddisheKop\LaravelCommerce\Models\Offer;
use YiddisheKop\LaravelCommerce\Models\OrderItem;

trait HandlesCartItems
{

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function add(Purchasable $product, int $quantity = 1, array $options = null): self
    {

        $existingItem = $this->items()
            ->where('model_id', $product->id)
            ->where('model_type', get_class($product))
            ->first();

        // if item is already in cart - just increment its quantity
        if ($existingItem) {
            $existingItem->increment('quantity', $quantity);

            // update options
            if ($options) {
                $existingItem->update([
                    'options' => $options
                ]);
            }

            return $this;
        }

        $this->items()->create([
            'model_id' => $product->id,
            'model_type' => get_class($product),
            'title' => $product->getTitle(),
            'price' => $product->getPrice($this->currency, $options),
            'quantity' => $quantity,
            'options' => $options,
        ]);

        event(new AddedToCart($this, $product));

        return $this;
    }

    public function updateItem(Purchasable $product, int $quantity = 1, array $options = null): self
    {
        $existingItem = $this->items()
            ->where('model_id', $product->id)
            ->where('model_type', get_class($product))
            ->first();

        if ($existingItem) {
            $updateData = ['quantity' => $quantity];
            $options && $updateData['options'] = $options;
            $existingItem->update($updateData);
        }

        return $this;
    }

    public function remove(Purchasable $product): self
    {
        OrderItem::where('model_id', $product->id)
            ->where('model_type', get_class($product))
            ->delete();
        return $this;
    }

    public function empty()
    {
        $this->items()->delete();
    }

    public function applyCoupon(string $code)
    {
        if ($coupon = Coupon::where('code', $code)->first()) {
            return $coupon->apply($this);
        } else {
            throw new CouponNotFound("Invalid coupon code", 1);
        }
    }

    public function removeCoupon()
    {
        $this->update([
            'coupon_id' => null
        ]);
    }

    private function getItemsTotal()
    {
        if ($offersCalculator = config('commerce.offers.calculator')) {
            $offersCalculator::apply($this);
        }

        return $this->items->sum(fn ($item) => ($item->price - $item->discount) * $item->quantity);
    }

    private function getShippingTotal()
    {
        if ($shippingCalculator = config('commerce.shipping.calculator')) {
            return (new $shippingCalculator)->calculate($this);
        }
        return config('commerce.shipping.cost') * 100;
    }

    private function getCouponDiscount($itemsTotal, $shippingTotal)
    {
        $couponDiscount = 0;
        $originalPrice = $itemsTotal;
        config('commerce.coupon.include_shipping') && $originalPrice += $shippingTotal;

        if ($this->coupon) {
            $couponDiscount = $this->coupon->calculateDiscount($originalPrice);
        }
        return $couponDiscount;
    }

    public function calculateTotals(): self
    {

        $this->refreshItems();

        $itemsTotal = $this->getItemsTotal();
        $shippingTotal = $this->getShippingTotal();

        if (config('commerce.coupon.include_tax')) {
            // calculate tax, then coupon
            $taxTotal = $this->calculateTax($itemsTotal);
            $couponDiscount = $this->getCouponDiscount($itemsTotal + $taxTotal, $shippingTotal);
        } else {
            // calculate coupon, then tax
            $couponDiscount = $this->getCouponDiscount($itemsTotal, $shippingTotal);
            $taxTotal = $this->calculateTax($itemsTotal, $couponDiscount);
        }

        // TODO: config('commerce.tax.included_in_prices')
        $grandTotal = ($itemsTotal + $taxTotal + $shippingTotal) - $couponDiscount;

        $this->update([
            'items_total' => $itemsTotal,
            'coupon_total' => $couponDiscount,
            'tax_total' => $taxTotal,
            'shipping_total' => $shippingTotal,
            'grand_total' => $grandTotal,
        ]);
        return $this;
    }

    /**
     *  Calculate tax_total
     */
    public function calculateTax(&$itemsTotal, $couponDiscount = 0)
    {
        $taxableAmount = $itemsTotal - $couponDiscount;
        if (config('commerce.tax.included_in_prices')) {
            $taxTotal = Vat::of($taxableAmount);
            $itemsTotal -= $taxTotal;
        } else {
            $taxTotal = round($taxableAmount * config('commerce.tax.rate')); // add vat
        }
        return $taxTotal;
    }

    /**
     *  Refresh price data from Purchasable model
     *  Apply Offer
     *  Remove deleted products from the cart
     *
     *  (we can't use a constraint, as it's a morphable relationship)
     */
    protected function refreshItems()
    {

        $cartItems = $this->items()
            ->with('model')
            ->get();

        $offer = Offer::getFor($this);

        $cartItems->each(function (OrderItem $item) use ($offer) {
            if (!$item->model) { // product has been deleted
                return $item->delete(); // also remove from cart
            }
            if ($offer && $offer->isValidFor($item)) {
                $offer->apply($item);
            } else {
                $item->update([
                    'title' => $item->model->getTitle(),
                    'price' => $item->model->getPrice($this->currency, $item->options),
                    'discount' => 0,
                ]);
            }
        });

        $this->refresh();
    }
}
