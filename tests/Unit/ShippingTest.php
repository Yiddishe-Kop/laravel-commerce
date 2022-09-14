<?php

use YiddisheKop\LaravelCommerce\Tests\Fixtures\Product;
use YiddisheKop\LaravelCommerce\Helpers\ExampleShippingCalculator;

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
});

it('applies simple flat shipping rate', function () {
    config([
        'commerce.shipping.calculator' => null,
        'commerce.shipping.cost'       => 12,
    ]);
    $this->cart->calculateTotals();
    expect($this->cart->shipping_total)->toEqual(1200);
});

it('can calculate shipping through the class', function () {
    config([
        'commerce.shipping.calculator' => ExampleShippingCalculator::class,
        'commerce.shipping.cost'       => 12,
    ]);
    $this->cart->calculateTotals();
    expect($this->cart->shipping_total)->toEqual(2400);
});
