<?php

namespace YiddisheKop\LaravelCommerce\Traits;

use YiddisheKop\LaravelCommerce\Contracts\Purchasable;
use YiddisheKop\LaravelCommerce\Models\OrderItem;

trait HandlesCartItems {

  public function items() {
    return $this->hasMany(OrderItem::class);
  }

  public function add(Purchasable $product, int $quantity = 1): self {

    $existingItem = $this->items()
      ->where('model_id', $product->id)
      ->where('model_type', get_class($product))
      ->first();

    // if item is already in cart - just increment its quantity
    if ($existingItem) {
      $existingItem->increment('quantity', $quantity);
      return $this;
    }

    $this->items()->create([
      'model_id' => $product->id,
      'model_type' => get_class($product),
      'quantity' => $quantity,
    ]);
    return $this;
  }

  public function remove(Purchasable $product): self {
    OrderItem::where('model_id', $product->id)
      ->where('model_type', get_class($product))
      ->delete();
    return $this;
  }

  public function calculateTotals($currency = null): self {

    $currency ??= config('commerce.currency');

    $this->loadMissing('items.model');

    $itemsTotal = $this->items->sum(fn ($item) => $item->model->getPrice($currency) * $item->quantity);
    $taxRate = config('commerce.tax.rate');
    $taxTotal = round(($itemsTotal / 100) * $taxRate);
    $grandTotal = $itemsTotal + $taxTotal;

    $this->update([
      'currency' => $currency,
      'items_total' => $itemsTotal,
      'tax_total' => $taxTotal,
      'grand_total' => $grandTotal,
    ]);
    return $this;
  }

  /**
   *  Remove deleted products from the cart
   *
   *  (we can't use a constraint, as it's a morphable relationship)
   */
  public function cleanupItems() {
      // done at deleting the product
  }
}
