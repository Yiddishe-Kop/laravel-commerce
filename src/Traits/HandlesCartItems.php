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
      'title' => $product->getTitle(),
      'price' => $product->getPrice($this->currency),
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

  public function empty() {
    $this->items()->delete();
  }

  public function calculateTotals(): self {

    $this->refreshItems();

    $itemsTotal = $this->items->sum(fn ($item) => $item->price * $item->quantity);
    $taxRate = config('commerce.tax.rate');
    $taxTotal = round(($itemsTotal / 100) * $taxRate);
    $grandTotal = $itemsTotal + $taxTotal;

    $this->update([
      'items_total' => $itemsTotal,
      'tax_total' => $taxTotal,
      'grand_total' => $grandTotal,
    ]);
    return $this;
  }

  /**
   *  Refresh price data from Purchasable model
   *  Remove deleted products from the cart
   *
   *  (we can't use a constraint, as it's a morphable relationship)
   */
  protected function refreshItems() {
    $this->items()
      ->with('model')
      ->get()
      ->each(function (OrderItem $item) {
        if (!$item->model) {
          return $item->delete();
        }
        $item->update([
          'title' => $item->model->getTitle(),
          'price' => $item->model->getPrice($this->currency),
        ]);
      });
    $this->refresh();
  }
}
