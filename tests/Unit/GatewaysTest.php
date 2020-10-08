<?php

namespace YiddisheKop\LaravelCommerce\Tests;

use YiddisheKop\LaravelCommerce\Gateway;

class GatewaysTest extends TestCase {

  private $gateways;

  public function setUp(): void {
    $this->gateways = Gateway::gateways();
  }

  /** @test */
  public function it_boots_the_payment_gateways() {
    $this->assertNotEmpty($this->gateways);
    // dump(collect($this->gateways)->pluck('name')->toArray());
  }

  /** @test */
  public function each_gateway_can_perform_a_purchase() {
    foreach ($this->gateways as $gateway) {
      $instance = new $gateway['class']();
      $result = $instance->purchase([], []);
      $this->assertEquals("Purchased with {$instance->name()}!", $result);
    }
  }
}
