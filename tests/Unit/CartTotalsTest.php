<?php

use YiddisheKop\LaravelCommerce\Tests\Fixtures\Product;

beforeEach(function () {
  $this->cart
    ->add(Product::create([
      'title' => 'BA Ziporen',
      'price' => 333
    ]), 2)
    ->add(Product::create([
      'title' => 'BA Vilna',
      'price' => 444
    ]), 5);
});


it('calculates the totals', function () {

  $this->cart->calculateTotals();

  $expectedItemsTotal = (333 * 2) + (444 * 5);
  $expectedTaxTotal = round(($expectedItemsTotal / 100) * config('commerce.tax.rate'));

  $this->assertEquals(
    $expectedItemsTotal,
    $this->cart->items_total
  );

  $this->assertEquals(
    $expectedTaxTotal,
    $this->cart->tax_total
  );

  $this->assertEquals(
    $expectedItemsTotal + $expectedTaxTotal,
    $this->cart->grand_total
  );
});
