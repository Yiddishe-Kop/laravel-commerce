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

    $options = [
      'BRANDNAME' => 'בית אלף',
      'LOGOIMG' => 'https://fonts.beitalef.com/img/favicon-32x32.png',
      'CHANNELTYPE' => 'Merchant'
    ];

    // $paypal->setCurrency($order->currency);
    $response = $paypal->addOptions($options)->setExpressCheckout([
      'items' => $items,
      'invoice_id' => $order->id,
      'invoice_description' => 'Test',
      'cancel_url' => route('cart.show'),
      'return_url' => route('order.complete', $order->id),
      'tax' => $order->tax_total,
      'subtotal' => $order->items_total,
      'total' => $order->grand_total,
    ]);

    $order->update(['gateway' => self::class]);

    return response('', 409)
      ->header('X-Inertia-Location', $response['paypal_link']);
  }

  public function complete(Order $order, Request $request) {
    $paypal = PayPalFacade::setProvider('express_checkout');
    $items = $this->formatLineItems($order);
    $response = $paypal->doExpressCheckoutPayment([
      'items' => $items,
      'invoice_id' => $order->id,
      'invoice_description' => 'Test',
      'cancel_url' => route('cart.show'),
      'return_url' => route('order.complete', $order->id),
      'tax' => $order->tax_total,
      'subtotal' => $order->items_total,
      'total' => $order->grand_total,
    ], $request->input('token'), $request->input('PayerID'));

    if ($response['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed') {
      $order->update([
        'gateway' => self::class,
        'gateway_data' => $response,
      ]);
      $order->markAsCompleted();
      return redirect()->route('checkout.thanks', ['order_id' => $order->id]);
    }
    return redirect()->route('checkout.failure');
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
