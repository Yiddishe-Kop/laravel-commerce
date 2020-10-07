<?php

namespace YiddisheKop\LaravelCommerce\Traits;

use YiddisheKop\LaravelCommerce\Contracts\Purchasable;
use YiddisheKop\LaravelCommerce\Models\OrderItem;

trait InteractsWithCartItems {

  public function cartItems() {
    return $this->hasMany(OrderItem::class);
  }

  public function add(Purchasable $product, int $quantity = 1) {
    return $this->orderItems()->create([
      'model_id' => $product->id,
      'model_type' => get_class($product),
      'title' => $product->getTitle(),
      'price' => $product->getPrice(),
      'quantity' => $quantity,
    ]);
  }

  public function remove(int $id) {
    return OrderItem::where('model_id', $id)->delete();
  }

  public function calculateTotals() {
    $itemsTotal = $this->cartItems->sum(fn ($item) => $item->price * $item->quantity);
    $taxRate = config('commerce.tax.rate');
    $taxTotal = round(($itemsTotal / 100) * $taxRate);
    $grandTotal = $itemsTotal + $taxTotal;

    $this->update([
      'items_total' => $itemsTotal,
      'tax_total' => $taxTotal,
      'grand_total' => $grandTotal,
    ]);
  }
}
