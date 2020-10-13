<?php

namespace YiddisheKop\LaravelCommerce\Gateways;

use Illuminate\Http\Request;
use Srmklive\PayPal\Facades\PayPal as PayPalFacade;
use YiddisheKop\LaravelCommerce\Contracts\Gateway;
use YiddisheKop\LaravelCommerce\Models\Order;

class PayPal implements Gateway {

  public static function name(): string {
    return 'PayPal';
  }

  public function purchase(Order $order, Request $request) {
    $order->calculateTotals();
    $paypal = PayPalFacade::setProvider('express_checkout');
    $orderData = $this->orderData($order);

    $options = [
      'BRANDNAME' => 'בית אלף',
      'LOGOIMG' => 'https://fonts.beitalef.com/img/favicon-32x32.png',
      'CHANNELTYPE' => 'Merchant'
    ];

    // $paypal->setCurrency($order->currency);
    $response = $paypal->addOptions($options)->setExpressCheckout($orderData);

    $order->update(['gateway' => self::class]);

    // redirect to PayPal express checkout
    return response('', 409)
      ->header('X-Inertia-Location', $response['paypal_link']);
  }

  public function complete(Order $order, Request $request) {
    $paypal = PayPalFacade::setProvider('express_checkout');
    $orderData = $this->orderData($order);

    // execute the payment with the recieved credentials
    $response = $paypal
      ->doExpressCheckoutPayment(
        $orderData,
        $request->input('token'),
        $request->input('PayerID')
      );

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
    // not used
  }

  protected function orderData(Order $order): array {
    return [
      'items' => $this->formatLineItems($order),
      'invoice_id' => $order->id,
      'invoice_description' => 'Test',
      'cancel_url' => route('cart.show'),
      'return_url' => route('order.complete', $order->id),
      'tax' => $order->tax_total,
      'subtotal' => $order->items_total,
      'total' => $order->grand_total,
    ];
  }

  protected function formatLineItems(Order $order): array {
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
