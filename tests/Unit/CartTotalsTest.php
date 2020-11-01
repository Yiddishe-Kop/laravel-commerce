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
  $shipping = config('commerce.shipping.cost');

  $this->assertEquals(
    $expectedItemsTotal,
    $this->cart->items_total
  );

  $this->assertEquals(
    $expectedTaxTotal,
    $this->cart->tax_total
  );

  $this->assertEquals(
    $expectedItemsTotal + $expectedTaxTotal + $shipping,
    $this->cart->grand_total
  );
});

test('if the price has changed, the cart will update the price upon calculating the totals', function() {

  $this->cart->empty();

  $product = Product::create([
    'title' => 'My awesome product',
    'price' => 111
  ]);

  $this->cart->add($product);
  $this->cart->calculateTotals();
  expect($this->cart->items_total)->toEqual(111);

  $product->update([
    'price' => 222
  ]);
  $cart = $this->cart->calculateTotals();
  expect($cart->items_total)->toEqual(222);

});
