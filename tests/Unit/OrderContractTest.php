l<?php

use YiddisheKop\LaravelCommerce\Facades\Cart;
use YiddisheKop\LaravelCommerce\Models\Order;
use YiddisheKop\LaravelCommerce\Tests\Fixtures\MyOrder;
use YiddisheKop\LaravelCommerce\Tests\Fixtures\Product;
use YiddisheKop\LaravelCommerce\Tests\Fixtures\User;

beforeEach(function () {
    $this->cart
        ->add(Product::create([
            'title' => 'BA Ziporen',
            'price' => 333
        ]), 2)
        ->add(Product::create([
            'title' => 'BA Vilna',
            'price' => 444
        ]), 5);

    $this->user = User::create([
        'name' => 'Yehuda',
        'email' => 'yehuda@yiddishe-kop.com',
        'password' => '12345678'
    ]);
});

test('you can set the config to use a custom Order model', function () {
    config([
        'commerce.models.order' => Order::class,
    ]);
    $order = Cart::get();
    expect($order)->toBeInstanceOf(Order::class);

    config([
        'commerce.models.order' => MyOrder::class,
    ]);
    $order = Cart::get();
    expect($order)->toBeInstanceOf(MyOrder::class);
});

test('you can use a custom Order class by implementing the Order contract', function () {

    /** @var MyOrder $myOrder */
    $myOrder = MyOrder::find($this->cart->id);
    $myOrder->update([
        'user_id' => $this->user->id
    ]);

    $myOrder->calculateTotals();
    $this->assertEquals($myOrder->items_total, 0);
    $this->assertEquals($myOrder->tax_total, 0);
    $this->assertEquals($myOrder->coupon_total, 0);
    $this->assertEquals($myOrder->shipping_total, 0);
    $this->assertEquals($myOrder->grand_total, 0);

    $myOrder->markAsCompleted();
    $this->assertEquals(Order::STATUS_COMPLETED, $myOrder->status);
    $this->assertTrue(today()->isSameDay($myOrder->paid_at));
});
