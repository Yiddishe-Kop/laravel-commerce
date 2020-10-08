<?php

namespace YiddisheKop\LaravelCommerce\Http\Controllers;

use Illuminate\Http\Request;
use YiddisheKop\LaravelCommerce\Facades\Cart;

class CartItemController extends Controller {

  public function store(Request $request) {

    $cart = Cart::get();

    return response()->json([
      'cart' => $cart->id,
    ]);
  }

}
