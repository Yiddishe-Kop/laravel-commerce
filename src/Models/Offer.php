<?php

namespace YiddisheKop\LaravelCommerce\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model {

  const TYPE_PERCENTAGE = 'percentage';
  const TYPE_FIXED = 'fixed';

  protected $guarded = [];

  /**
   * Get first available offer for the order
   */
  public static function getFor(Order $order) {
    $productTypeCounts = $order->items
      ->groupBy('model_type')
      ->mapWithKeys(function ($item, $key) {
        return [$key => $item->count()];
      });

    $validOffers = collect();
    $offers = Offer::orderBy('min', 'desc')->get();

    $offers->each(function ($offer) use ($validOffers, $productTypeCounts) {
      if (!$offer->product_type) { // offer is valid for all product types
        $validOffers->push($offer);
        return;
      } else if ($productTypeCounts->has($offer->product_type)) { // the required product type is in the cart
        $amountInCart = $productTypeCounts[$offer->product_type];
        if ($amountInCart >= $offer->min) { // right amount in cart
          // if ($offer->max && $amountInCart > $offer->max) return; // too many
          $validOffers->push($offer);
        }
      }
    });

    $appliedOffer = $validOffers->first();

    return $appliedOffer;
  }

  /**
   * Apply an offer on a orderItem
   */
  public function apply(OrderItem $item) {

    $product = $item->model;
    $order = $item->order;

    $originalPrice = $product->getPrice($order->currency, $item->options);

    $discount = 0;
    if ($this->type == self::TYPE_FIXED) {
      $discount = $this->discount;
    } else if ($this->type == self::TYPE_PERCENTAGE) {
      $discount = ($originalPrice / 100) * $this->discount;
    }
    $item->update([
      'title' => $product->getTitle(),
      'price' => $originalPrice,
      'discount' => $discount
    ]);
  }
}
