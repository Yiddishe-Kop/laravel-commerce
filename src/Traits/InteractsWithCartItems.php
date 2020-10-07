<?php

namespace YiddisheKop\LaravelCommerce\Traits;

use YiddisheKop\LaravelCommerce\Models\OrderItem;

trait InteractsWithCartItems {

  public function addItem($product) {
    return $this->orderItems()->create([
      'model_id' => $product->id,
      'model_type' => get_class($product),
      'price' => $product->price
    ]);
  }

  public function removeItem(int $id) {
    return OrderItem::where('model_id', $id)->delete();
  }
}
