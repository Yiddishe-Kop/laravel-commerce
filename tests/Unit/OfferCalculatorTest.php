<?php

use YiddisheKop\LaravelCommerce\Tests\Fixtures\Product;

beforeEach(function () {
    $this->cart
        ->add(Product::create([
            'title' => 'Macbook Air',
            'price' => 1000,
        ]), 10)
        ->add(Product::create([
            'title' => 'Macbook Pro',
            'price' => 2000,
        ]));
});

it('applies calculated offer', function () {
    $this->cart->calculateTotals();

    expect($this->cart->items_total)->toEqual(7000 * 100);
});

it('doesnt apply calculated offer if not in config', function () {
    config([
        'commerce.offers.calculator' => null,
    ]);

    $this->cart->calculateTotals();

    expect($this->cart->items_total)->toEqual(12000 * 100);
});
