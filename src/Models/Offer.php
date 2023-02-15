<?php

namespace YiddisheKop\LaravelCommerce\Models;

use Illuminate\Database\Eloquent\Model;
use YiddisheKop\LaravelCommerce\Contracts\Order;

class Offer extends Model
{
    public const TYPE_PERCENTAGE = 'percentage';
    public const TYPE_FIXED = 'fixed';

    protected $guarded = [];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_to'   => 'datetime',
    ];

    // scopes
    public function scopeValid($q)
    {
        $q->where(function ($q) {
            $q->where('valid_from', '<', now())
                ->orWhereNull('valid_from');
        })->where(function ($q) {
            $q->where('valid_to', '>', now())
                ->orWhereNull('valid_to');
        });
    }

    /**
     * Get first available offer for the order
     */
    public static function getFor(Order $order)
    {
        $productTypeCounts = $order->items
            ->groupBy('model_type')
            ->mapWithKeys(function ($item, $key) {
                return [$key => $item->count()];
            });

        $validOffers = collect();

        $offers = self::valid()
            ->orderBy('min', 'desc')
            ->get();

        $offers->each(function ($offer) use ($validOffers, $productTypeCounts) {
            if (! $offer->product_type) { // offer is valid for all product types
                $validOffers->push($offer);

                return;
            } elseif ($productTypeCounts->has($offer->product_type)) { // the required product type is in the cart
                $amountInCart = $productTypeCounts[$offer->product_type];
                if ($amountInCart >= $offer->min) { // right amount in cart
                    $validOffers->push($offer);
                }
            }
        });

        $appliedOffer = $validOffers->first();

        return $appliedOffer;
    }

    public function isValidFor(OrderItem $item)
    {
        if (! $this->product_type) {
            return true;
        }

        return $this->product_type == $item->model_type;
    }

    /**
     * Apply an offer on a orderItem
     */
    public function apply(OrderItem $item)
    {
        $product = $item->model;
        $order = $item->order;

        $originalPrice = $product->getPrice($order->currency, $item->options);

        $discount = 0;
        if ($this->type == self::TYPE_FIXED) {
            $discount = $this->discount;
        } elseif ($this->type == self::TYPE_PERCENTAGE) {
            $discount = ($originalPrice / 100) * $this->discount;
        }
        $item->update([
            'title'    => $product->getTitle(),
            'price'    => $originalPrice,
            'discount' => $discount,
        ]);
    }
}
