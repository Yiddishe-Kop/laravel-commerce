<?php

use Illuminate\Support\Facades\Event;
use YiddisheKop\LaravelCommerce\Cart;
use YiddisheKop\LaravelCommerce\Events\CouponRedeemed;
use YiddisheKop\LaravelCommerce\Exceptions\CouponExpired;
use YiddisheKop\LaravelCommerce\Exceptions\CouponLimitReached;
use YiddisheKop\LaravelCommerce\Exceptions\CouponNotFound;
use YiddisheKop\LaravelCommerce\Listeners\IncrementCouponTimesUsed;
use YiddisheKop\LaravelCommerce\Models\Coupon;
use YiddisheKop\LaravelCommerce\Tests\Fixtures\Product;

beforeEach(function () {
  $this->cart
    ->add(Product::create([
      'title' => 'Macbook Air',
      'price' => 1000
    ]))
    ->add(Product::create([
      'title' => 'Macbook Pro',
      'price' => 2000
    ]));

  $this->coupon = Coupon::create([
    'code' => 'BLACK-FRIDAY-2020',
    'type' => Coupon::TYPE_PERCENTAGE,
    'valid_from' => now()->subMinute(),
    'valid_to' => now()->addMonth(),
    'discount' => 10,
    'times_used' => 0,
  ]);
});

test('Can apply FIXED coupon to order', function () {
  $coupon = Coupon::create([
    'code' => 'BLACK-FRIDAY-2021',
    'type' => Coupon::TYPE_FIXED,
    'discount' => 200,
  ]);

  $this->cart->applyCoupon($coupon->code);
  $this->cart->calculateTotals();

  expect($this->cart->coupon->id)->toBe($coupon->id);
  expect($this->cart->coupon_total)->toEqual($coupon->discount);
  // shipping: 12
  // tax: 600
  // itemsTotal: 3612
  expect($this->cart->grand_total)->toEqual(3612 - 200);
});

test('Can apply PERCENTAGE coupon to order', function () {

  $this->cart->applyCoupon($this->coupon->code);
  $this->cart->calculateTotals();

  expect($this->cart->coupon->id)->toBe($this->coupon->id);
  // shipping: 12
  // tax: 600
  // itemsTotal: 3612
  // expected coupon discount: 361.2
  expect($this->cart->coupon_total)->toEqual(361.2);
  expect($this->cart->grand_total)->toEqual(3612 - 361.2);
});

it('throws an exception if trying to apply invalid coupon code', function () {
  $this->expectException(CouponNotFound::class);
  $this->cart->applyCoupon('NON-EXISTING-COUPON-1935');
});

it('throws an exception if coupon reached max_uses', function () {
  $this->coupon->update([
    'max_uses' => 1,
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
    'user_id' => 1
  ]);
  $this->cart->markAsCompleted();
  Event::assertDispatched(CouponRedeemed::class, fn ($event) => $event->coupon->id == $this->coupon->id);
});

test('Coupon times_used incremented', function () {
  $this->cart->applyCoupon($this->coupon->code);
  $this->cart->calculateTotals();
  $this->cart->update([
    'user_id' => 1
  ]);
  $this->cart->markAsCompleted();
  $this->coupon->refresh();
  expect($this->coupon->times_used)->toEqual(1);
});
