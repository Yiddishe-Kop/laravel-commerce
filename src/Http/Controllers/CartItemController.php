<?php

namespace YiddisheKop\LaravelCommerce\Http\Controllers;

use Illuminate\Http\Request;
use YiddisheKop\LaravelCommerce\Facades\Cart;
use YiddisheKop\LaravelCommerce\Models\OrderItem;

class CartItemController extends Controller {

  public function store(Request $request) {

    $request->validate([
      'product_type' => 'required|string',
      'product_id' => 'required',
      'quantity' => 'nullable|numeric',
    ]);

    $product = $request->product_type::findOrFail($request->product_id);
    Cart::add($product, $request->quantity ?? 1);

    return back()->with('success', 'Product has been added to your cart.');
  }

  public function destroy(OrderItem $item) {
    $item->delete();
    return back()->with('success', 'Product has been removed from your cart.');
  }
}
