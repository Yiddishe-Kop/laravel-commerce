<?php

use YiddisheKop\LaravelCommerce\Models\Coupon;
use YiddisheKop\LaravelCommerce\Tests\Fixtures\Product;
use YiddisheKop\LaravelCommerce\Exceptions\CouponNotFound;

beforeEach(function () {
    $this->macbookAir = Product::create([
        'title' => 'Macbook Air',
        'price' => 1000,
    ]);

    $this->cart
        ->add($this->macbookAir)
        ->add(Product::create([
            'title' => 'Macbook Pro',
            'price' => 2000,
        ]));

    $this->coupon = Coupon::create([
        'code'         => 'BLACK-FRIDAY-2021',
        'type'         => Coupon::TYPE_PERCENTAGE,
        'valid_from'   => now()->subMinute(),
        'valid_to'     => now()->addMonth(),
        'discount'     => 10,
        'times_used'   => 0,
        'product_type' => Product::class,
        'product_id'   => $this->macbookAir->id,
    ]);

    config([
        'commerce.shipping.calculator' => null,
        'commerce.offers.calculator'   => null,
    ]);
});

test('Applies coupon to restricted product only', function () {
    $this->cart->applyCoupon($this->coupon->code);
    $this->cart->calculateTotals();

    expect($this->cart->coupon->id)->toBe($this->coupon->id);
    // itemsTotal: 300000
    // tax: 60000
    // shipping: 1200
    // expected coupon discount: 10120
    expect($this->cart->coupon_total)->toEqual(10120);
    expect($this->cart->grand_total)->toEqual(361200 - 10120);
});

it('throws an exception if coupon not valid for any items in order', function () {
    $this->coupon->update([
        'product_id' => 999,
    ]);
    $this->expectException(CouponNotFound::class);
    $this->cart->applyCoupon($this->coupon->code);
});
