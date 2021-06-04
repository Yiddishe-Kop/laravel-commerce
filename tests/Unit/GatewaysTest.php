<?php

use YiddisheKop\LaravelCommerce\Gateway;

beforeEach(function () {
    $this->gateways = Gateway::gateways();
});

it('boots the payment gateways', function () {
    $this->assertNotEmpty($this->gateways);
});

test('each gateway has a name', function () {
    foreach ($this->gateways as $gateway) {
        expect($gateway['class']::name())->not->toBeEmpty();
    }
});
