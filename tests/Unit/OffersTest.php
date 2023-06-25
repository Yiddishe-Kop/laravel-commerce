<?php

use YiddisheKop\LaravelCommerce\Models\Offer;
use YiddisheKop\LaravelCommerce\Tests\Fixtures\Package;
use YiddisheKop\LaravelCommerce\Tests\Fixtures\Product;

beforeEach(function () {

    $this->product1 = Product::create([
        'title' => 'Mercedes S300',
        'price' => 3000,
    ]);
    $this->product2 = Product::create([
        'title' => 'Audi A8',
        'price' => 2000,
    ]);
    $this->product3 = Package::create([
        'title' => 'Hotel Weekend Package',
        'price' => 4000,
    ]);

    $this->cart
        ->add($this->product1)
        ->add($this->product2)
        ->add($this->product3);

    config([
        'commerce.shipping.calculator' => null,
        'commerce.offers.calculator'   => null,
    ]);
});

test('Offers get applied to cart total', function () {
    Offer::create([
        'type'         => Offer::TYPE_PERCENTAGE,
        'discount'     => 10,
        'product_type' => Product::class,
    ]);

    $this->cart->calculateTotals();

    expect($this->cart->items_total)->toEqual(8500 * 100);
});

test('Offers can be limited to product_ids', function () {
    Offer::create([
        'type'         => Offer::TYPE_PERCENTAGE,
        'discount'     => 50,
        'product_ids' => [$this->product2->id],
    ]);

    $this->cart->calculateTotals();

    expect($this->cart->items_total)->toEqual(8000 * 100);
});

test('Multiple Offers are applied', function () {
    Offer::create([
        'type'         => Offer::TYPE_PERCENTAGE,
        'discount'     => 50,
        'product_ids' => [$this->product1->id],
    ]);
    Offer::create([
        'type'         => Offer::TYPE_PERCENTAGE,
        'discount'     => 75,
        'product_ids' => [$this->product2->id],
    ]);

    $this->cart->calculateTotals();

    expect($this->cart->items_total)->toEqual(6000 * 100);
});

test('Offers only get applied to specified product type', function () {
    Offer::create([
        'type'         => Offer::TYPE_PERCENTAGE,
        'discount'     => 50,
        'product_type' => Package::class,
    ]);

    $this->cart->calculateTotals();

    expect($this->cart->items_total)->toEqual(7000 * 100);
});

test('The right Offer with highest min gets applied', function () {
    $this->cart->empty();

    $this->cart
        ->add(Product::create([
            'title' => 'Mercedes S300',
            'price' => 1000,
        ]), 6);

    Offer::create([
        'type'     => Offer::TYPE_FIXED,
        'discount' => 100,
        'min'      => 3,
    ]);

    // This offer should get applied, as min is higher, and 6 in cart.
    Offer::create([
        'type'     => Offer::TYPE_FIXED,
        'discount' => 200,
        'min'      => 6,
    ]);

    $this->cart->calculateTotals();

    expect($this->cart->items_total)->toEqual((6000 * 100) - (200 * 6));
});

test('Expired offers are not applied', function () {
    Offer::create([
        'type'       => Offer::TYPE_PERCENTAGE,
        'discount'   => 50,
        'valid_from' => now()->addMinute(),
    ]);

    Offer::create([
        'type'     => Offer::TYPE_PERCENTAGE,
        'discount' => 50,
        'valid_to' => now()->subMinute(),
    ]);

    $this->cart->calculateTotals();

    expect($this->cart->items_total)->toEqual(9000 * 100);

    Offer::create([
        'type'       => Offer::TYPE_PERCENTAGE,
        'discount'   => 50,
        'valid_from' => now()->subMinute(),
        'valid_to'   => now()->addMinute(),
    ]);

    $this->cart->calculateTotals();

    expect($this->cart->items_total)->toEqual(4500 * 100);
});

test('Offer doesn\'t get applied if minimum is not met', function () {
    Offer::create([
        'type'         => Offer::TYPE_PERCENTAGE,
        'discount'     => 20,
        'min'          => 3,
        'product_type' => Product::class,
    ]);

    $this->cart->calculateTotals();

    expect($this->cart->items_total)->toEqual(9000 * 100);
});
