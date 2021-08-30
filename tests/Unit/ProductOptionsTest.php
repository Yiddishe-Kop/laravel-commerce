<?php

use YiddisheKop\LaravelCommerce\Tests\Fixtures\Product;

beforeEach(function () {
    config([
        'commerce.shipping.calculator' => null,
        'commerce.offers.calculator' => null,
    ]);
});

it('can add product to cart with options, and update the options', function () {

    $product = Product::create([
        'title' => 'Shirt',
        'price' => 100
    ]);

    $this->cart->add(
        $product,
        1,
        ['size' => 'large']
    );

    $this->cart->calculateTotals();

    expect($this->cart->items_total)->toEqual(130 * 100);

    $this->cart->updateItem($product, 1, ['size' => 'small']);
    $this->cart->calculateTotals();

    expect($this->cart->items_total)->toEqual(110 * 100);

    $this->cart->updateItem($product, 2, ['size' => 'medium']);
    $this->cart->calculateTotals();

    expect($this->cart->items_total)->toEqual(240 * 100);
});
