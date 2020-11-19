<?php

use YiddisheKop\LaravelCommerce\Models\Offer;
use YiddisheKop\LaravelCommerce\Tests\Fixtures\Package;
use YiddisheKop\LaravelCommerce\Tests\Fixtures\Product;

beforeEach(function () {
  $this->cart
    ->add(Product::create([
      'title' => 'Mercedes S300',
      'price' => 3000
    ]))
    ->add(Product::create([
      'title' => 'Audi A8',
      'price' => 2000
    ]))
    ->add(Package::create([
      'title' => 'Hotel Weekend Package',
      'price' => 4000
    ]));
});

test('Offers get applied to cart total', function () {

  Offer::create([
    'type' => Offer::TYPE_PERCENTAGE,
    'discount' => 10,
    'product_type' => Product::class,
  ]);

  $this->cart->calculateTotals();

  expect($this->cart->items_total)->toEqual(8500);
});

test('Offer doesn\'t get applied if minimum is not met', function () {

  Offer::create([
    'type' => Offer::TYPE_PERCENTAGE,
    'discount' => 20,
    'min' => 3,
    'product_type' => Product::class,
  ]);

  $this->cart->calculateTotals();

  expect($this->cart->items_total)->toEqual(9000);
});