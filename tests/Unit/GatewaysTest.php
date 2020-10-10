<?php

use YiddisheKop\LaravelCommerce\Gateway;

beforeEach(function () {
  $this->gateways = Gateway::gateways();
});

it('boots the payment gateways', function() {
  $this->assertNotEmpty($this->gateways);
});

test('each gateway can perform a purchase', function () {
  foreach ($this->gateways as $gateway) {
    $instance = new $gateway['class']();
    $result = $instance->purchase([], []);
    $this->assertEquals("Purchased with {$instance->name()}!", $result);
  }
});
