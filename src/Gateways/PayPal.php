<?php

namespace YiddisheKop\LaravelCommerce\Gateways;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Facades\PayPal as PayPalFacade;
use Srmklive\PayPal\Services\ExpressCheckout;
use YiddisheKop\LaravelCommerce\Contracts\Gateway;
use YiddisheKop\LaravelCommerce\Models\Order;

class PayPal implements Gateway {

  public function name(): string {
    return 'PayPal';
  }

  public function purchase(Order $order, Request $request) {
    $order->calculateTotals();
    $paypal = PayPalFacade::setProvider('express_checkout');
    $items = $this->formatLineItems($order);

    // $paypal->setCurrency($order->currency);
    $response = $paypal->setExpressCheckout([
      'items' => $items,
      'invoice_id' => $order->id,
      'invoice_description' => 'Test',
      'cancel_url' => route('cart.show'),
      'return_url' => route('checkout.thanks'),
      'tax' => $order->tax_total,
      'subtotal' => $order->items_total,
      'total' => $order->grand_total,
    ]);

    dd($response);

    return response('', 409)
      ->header('X-Inertia-Location', $response['paypal_link']);
  }

  public function webhook(Request $request) {
    Log::info($request->input());
    $provider = new ExpressCheckout();

    $request->merge(['cmd' => '_notify-validate']);
    $post = $request->all();

    $response = (string) $provider->verifyIPN($post);

    if ($response === 'VERIFIED') {
      $order = Order::findOrFail($request->invoice_id);
      $order->update([
        'gateway' => self::class,
        'gateway_data' => $request->all(),
      ]);
      $order->markAsCompleted();
    }

    return response('', 200);
  }

  protected function formatLineItems(Order $order) {
    return $order->items()->get()->transform(function ($item) {
      return [
        'id' => $item->id,
        'model_id' => $item->model_id,
        'model_type' => $item->model_type,
        'name' => $item->title,
        'price' => $item->price * $item->quantity,
        'qty' => $item->quantity,
      ];
    })->toArray();
  }
}
