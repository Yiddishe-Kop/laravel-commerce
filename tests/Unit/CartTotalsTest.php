<?php

namespace YiddisheKop\LaravelCommerce\Tests;

use YiddisheKop\LaravelCommerce\Tests\Fixtures\Product;
class CartTotalsTest extends CommerceTestCase {


  public function setUp(): void {
    parent::setUp();

    $this->cart
      ->add(Product::create([
        'title' => 'BA Ziporen',
        'price' => 333
      ]), 2)
      ->add(Product::create([
        'title' => 'BA Vilna',
        'price' => 444
      ]), 5);

  }

  /** @test */
  public function it_calculates_the_totals() {

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
  }
}
