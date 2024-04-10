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
    // shipping: 1200
    // tax: 60,240
    // expected coupon discount: 120.00 [10% of Macbook Air price + tax: 1200.00]
    expect($this->cart->coupon_total)->toEqual(12000);
    expect($this->cart->grand_total)->toEqual(361440 - 12000);
});

it('throws an exception if coupon not valid for any items in order', function () {
    $this->coupon->update([
        'product_id' => 999,
    ]);
    $this->expectException(CouponNotFound::class);
    $this->cart->applyCoupon($this->coupon->code);
});

it('removes coupon if valid product removed from order', function () {
    $this->cart->applyCoupon($this->coupon->code);
    $this->cart->calculateTotals();

    expect($this->cart->coupon_id)->toBe($this->coupon->id);

    $this->cart->remove($this->macbookAir);
    $this->cart->calculateTotals();

    expect($this->cart->coupon_id)->toBeNull();
    expect($this->cart->coupon_total)->toEqual(0);
});
