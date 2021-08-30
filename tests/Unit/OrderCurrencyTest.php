<?php

use YiddisheKop\LaravelCommerce\Exceptions\OrderAlreadyComplete;
use YiddisheKop\LaravelCommerce\Tests\Fixtures\Product;
use YiddisheKop\LaravelCommerce\Tests\Fixtures\User;

beforeEach(function () {
    $this->cart
        ->add(Product::create([
            'title' => 'BA Ziporen',
            'price' => 200
        ]), 2);
    $this->user = User::create([
        'name' => 'Yehuda',
        'email' => 'yehuda@yiddishe-kop.com',
        'password' => '12345678'
    ]);

    config([
        'commerce.shipping.calculator' => null,
        'commerce.offers.calculator' => null,
    ]);
});


it('can set the order currency', function () {

    expect($this->cart->currency)->toBe('USD');

    $this->cart->setCurrency('GBP');

    expect($this->cart->currency)->toBe('GBP');
});

it('can recalculate the totals for new currency', function () {

    $this->cart->calculateTotals();

    expect($this->cart->currency)->toBe('USD');
    expect($this->cart->items_total)->toEqual(400 * 100);

    $this->cart->setCurrency('GBP');

    expect($this->cart->currency)->toBe('GBP');
    expect($this->cart->items_total)->toEqual(200 * 100);
});

it('it throws an exception when changing the currency of a completed order', function () {

    $this->cart->update([
        'user_id' => $this->user->id
    ]);
    $this->cart->calculateTotals();
    $this->cart->markAsCompleted();

    $this->expectException(OrderAlreadyComplete::class);
    $this->cart->setCurrency('GBP');
});
