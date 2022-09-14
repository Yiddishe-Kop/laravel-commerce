<?php

use Illuminate\Support\Facades\Event;
use YiddisheKop\LaravelCommerce\Helpers\Vat;
use YiddisheKop\LaravelCommerce\Models\Coupon;
use YiddisheKop\LaravelCommerce\Events\CouponRedeemed;
use YiddisheKop\LaravelCommerce\Tests\Fixtures\Product;
use YiddisheKop\LaravelCommerce\Exceptions\CouponExpired;
use YiddisheKop\LaravelCommerce\Exceptions\CouponNotFound;
use YiddisheKop\LaravelCommerce\Exceptions\CouponLimitReached;

beforeEach(function () {
    $this->cart
        ->add(Product::create([
            'title' => 'Macbook Air',
            'price' => 1000,
        ]))
        ->add(Product::create([
            'title' => 'Macbook Pro',
            'price' => 2000,
        ]));

    $this->coupon = Coupon::create([
        'code'       => 'BLACK-FRIDAY-2020',
        'type'       => Coupon::TYPE_PERCENTAGE,
        'valid_from' => now()->subMinute(),
        'valid_to'   => now()->addMonth(),
        'discount'   => 10,
        'times_used' => 0,
    ]);

    config([
        'commerce.shipping.calculator' => null,
        'commerce.offers.calculator'   => null,
    ]);
});

test('Can apply FIXED coupon to order', function () {
    $coupon = Coupon::create([
        'code'     => 'BLACK-FRIDAY-2021',
        'type'     => Coupon::TYPE_FIXED,
        'discount' => 200,
    ]);

    $this->cart->applyCoupon($coupon->code);
    $this->cart->calculateTotals();

    expect($this->cart->coupon_id)->toBe($coupon->id);
    expect($this->cart->coupon_total)->toEqual($coupon->discount);
    // shipping: 1200
    // tax: 60000
    // itemsTotal: 300000
    expect($this->cart->grand_total)->toEqual(361200 - 200);
});

test('Can apply FIXED coupon to order [config: exc. tax]', function () {
    config([
        'commerce.coupon.include_tax' => false,
    ]);

    $coupon = Coupon::create([
        'code'     => 'BLACK-FRIDAY-2021',
        'type'     => Coupon::TYPE_FIXED,
        'discount' => 200,
    ]);

    $this->cart->applyCoupon($coupon->code);
    $this->cart->calculateTotals();

    expect($this->cart->coupon->id)->toBe($coupon->id);
    expect($this->cart->coupon_total)->toEqual($coupon->discount);
    // shipping: 1200
    // itemsTotal: 300000
    // tax: 60000
    $expectedTaxTotal = Vat::for($this->cart->items_total - $this->cart->coupon_total);

    expect($this->cart->tax_total)->toEqual($expectedTaxTotal);
    expect($this->cart->grand_total)->toEqual($this->cart->items_total + $this->cart->shipping_total + $expectedTaxTotal - $this->cart->coupon_total);
});

test('Can apply PERCENTAGE coupon to order', function () {
    $this->cart->applyCoupon($this->coupon->code);
    $this->cart->calculateTotals();

    expect($this->cart->coupon->id)->toBe($this->coupon->id);
    // shipping: 1200
    // itemsTotal: 361200
    // expected coupon discount: 36120
    // tax: 60000
    expect($this->cart->coupon_total)->toEqual(36120);
    expect($this->cart->grand_total)->toEqual(361200 - 36120);
});

test('Can apply PERCENTAGE coupon to order  [config: exc. tax]', function () {
    config([
        'commerce.coupon.include_tax' => false,
    ]);

    $this->cart->applyCoupon($this->coupon->code);
    $this->cart->calculateTotals();

    expect($this->cart->coupon->id)->toBe($this->coupon->id);

    $expectedCouponTotal = ($this->cart->items_total + $this->cart->shipping_total) * 0.1;
    $expectedTaxTotal = Vat::for($this->cart->items_total - $this->cart->coupon_total);
    expect($this->cart->coupon_total)->toEqual($expectedCouponTotal);
    expect($this->cart->tax_total)->toEqual($expectedTaxTotal);
    expect($this->cart->grand_total)->toEqual($this->cart->items_total + $this->cart->tax_total + $this->cart->shipping_total - $expectedCouponTotal);
});

it('throws an exception if trying to apply invalid coupon code', function () {
    $this->expectException(CouponNotFound::class);
    $this->cart->applyCoupon('NON-EXISTING-COUPON-1935');
});

it('throws an exception if coupon reached max_uses', function () {
    $this->coupon->update([
        'max_uses'   => 1,
        'times_used' => 1,
    ]);
    $this->expectException(CouponLimitReached::class);
    $this->cart->applyCoupon($this->coupon->code);
});

it('throws an exception if coupon not yet valid', function () {
    $this->coupon->update([
        'valid_from' => now()->addMinute(),
    ]);
    $this->expectException(CouponExpired::class);
    $this->cart->applyCoupon($this->coupon->code);
});

it('throws an exception if coupon expired', function () {
    $this->coupon->update([
        'valid_to' => now()->subMinute(),
    ]);
    $this->expectException(CouponExpired::class);
    $this->cart->applyCoupon($this->coupon->code);
});

test('CouponRedeemed event fired', function () {
    Event::fake();
    $this->cart->applyCoupon($this->coupon->code);
    $this->cart->calculateTotals();
    $this->cart->update([
        'user_id' => 1,
    ]);
    $this->cart->markAsCompleted();
    Event::assertDispatched(CouponRedeemed::class, fn ($event) => $event->coupon->id == $this->coupon->id);
});

test('Coupon times_used incremented', function () {
    $this->cart->applyCoupon($this->coupon->code);
    $this->cart->calculateTotals();
    $this->cart->update([
        'user_id' => 1,
    ]);
    $this->cart->markAsCompleted();
    $this->coupon->refresh();
    expect($this->coupon->times_used)->toEqual(1);
});
