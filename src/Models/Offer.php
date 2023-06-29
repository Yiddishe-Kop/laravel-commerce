<?php

namespace YiddisheKop\LaravelCommerce\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use YiddisheKop\LaravelCommerce\Contracts\Order;

class Offer extends Model
{
    public const TYPE_PERCENTAGE = 'percentage';
    public const TYPE_FIXED = 'fixed';

    protected $guarded = [];

    protected $casts = [
        'product_ids' => 'array',
        'active'      => 'boolean',
        'valid_from'  => 'datetime',
        'valid_to'    => 'datetime',
    ];

    // scopes
    public function scopeValid($q)
    {
        $q->where('active', true)
        ->where(function ($q) {
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
    public static function getFor(Order $order): Collection
    {
        $productTypeCounts = $order->items
            ->groupBy('model_type')
            ->mapWithKeys(function ($item, $key) {
                return [$key => $item->sum('quantity')];
            });

        $validOffers = collect();

        self::valid()
            ->orderBy('min', 'desc')
            ->get()
            ->each(function (self $offer) use ($order, $validOffers, $productTypeCounts) {

                $productIds = collect($offer->product_ids);
                if ($productIds->isNotEmpty() && ! $productIds->intersect($order->items->pluck('model_id'))->count()) {
                    return;
                }

                if (! $offer->product_type) { // offer is valid for all product types
                    // offer is valid for all products
                    $validOffers->push($offer);

                    return;
                } elseif ($productTypeCounts->has($offer->product_type)) { // the required product type is in the cart
                    $amountInCart = $productTypeCounts[$offer->product_type];
                    if ($amountInCart >= $offer->min) { // right amount in cart
                        $validOffers->push($offer);
                    }
                }
            });

        // highest `min` first
        return $validOffers->reverse();
    }

    public function isValidFor(OrderItem $item)
    {
        $productIds = collect($this->product_ids);
        if ($productIds->isNotEmpty()) {
            if (! $productIds->contains($item->model_id)) {
                return false;
            }
        }

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
