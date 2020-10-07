<?php

namespace YiddisheKop\LaravelCommerce\Traits;

use YiddisheKop\LaravelCommerce\Contracts\Purchasable;
use YiddisheKop\LaravelCommerce\Models\OrderItem;

trait InteractsWithCartItems {

  public function addItem(Purchasable $product) {
    return $this->orderItems()->create([
      'model_id' => $product->id,
      'model_type' => get_class($product),
      'title' => $product->getTitle(),
      'price' => $product->getPrice()
    ]);
  }

  public function removeItem(int $id) {
    return OrderItem::where('model_id', $id)->delete();
  }
}
