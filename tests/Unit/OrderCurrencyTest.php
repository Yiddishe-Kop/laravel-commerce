<?php

use YiddisheKop\LaravelCommerce\Tests\Fixtures\Product;

beforeEach(function () {
  $this->cart
    ->add(Product::create([
      'title' => 'BA Ziporen',
      'price' => 200
    ]), 2);
});


it('can set the order currency', function () {

  expect($this->cart->currency)->toBe('USD');

  $this->cart->setCurrency('GBP');

  expect($this->cart->currency)->toBe('GBP');

});

it('can recalculate the totals for new currency', function () {

  $this->cart->calculateTotals();

  expect($this->cart->currency)->toBe('USD');
  expect($this->cart->items_total)->toEqual(400);

  $this->cart->setCurrency('GBP');

  expect($this->cart->currency)->toBe('GBP');
  expect($this->cart->items_total)->toEqual(200);

});
