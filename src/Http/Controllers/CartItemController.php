<?php

namespace YiddisheKop\LaravelCommerce\Http\Controllers;

use Illuminate\Http\Request;
use YiddisheKop\LaravelCommerce\Facades\Cart;
use YiddisheKop\LaravelCommerce\Models\OrderItem;

class CartItemController extends Controller
{

    public function store(Request $request)
    {

        $request->validate([
            'product_type' => 'required|string',
            'product_id' => 'required',
            'quantity' => 'nullable|numeric',
            'options' => 'nullable|array',
            'multi' => 'nullable|string'
        ]);

        $product = $request->product_type::findOrFail($request->product_id);

        if ($request->quantity > 1 &&  $request->multi) {
            $multiOptions = $request->options;
            foreach ($request->options[$request->multi] as  $value) {
                $multiOptions[$request->multi] = $value;
                Cart::add($product, 1, $multiOptions);
            }
        } else {
            Cart::add($product, $request->quantity ?? 1, $request->options);
        }

        return back()->with('success', __('Product has been added to your cart.', [
            'productTitle' => $product->getTitle()
        ]));
    }

    public function destroy(OrderItem $item)
    {
        $item->delete();

        return back()->with('success', __('Product has been removed from your cart.', [
            'productTitle' => $item->title
        ]));
    }
}