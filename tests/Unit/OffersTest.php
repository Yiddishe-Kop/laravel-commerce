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

    Offer::create([
      'type' => Offer::TYPE_PERCENTAGE,
      'discount' => 10,
      'product_type' => Product::class,
    ]);
    Offer::create([
      'type' => Offer::TYPE_PERCENTAGE,
      'discount' => 20,
      'min' => 3,
      'product_type' => Product::class,
    ]);
});

test('Offer has been created', function() {
  $offer = Offer::first();
  dump($offer->attributesToArray());
  expect($offer)->not()->toBeNull();
});

test('Offers get applied to cart total', function() {

  $this->cart->calculateTotals();

  expect($this->cart->items_total)->toEqual(8500);

})->only();
