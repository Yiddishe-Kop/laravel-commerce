<?php

use YiddisheKop\LaravelCommerce\Facades\Cart;
use YiddisheKop\LaravelCommerce\Tests\Fixtures\User;

test('the cart is attached to logged in user', function () {

  $this->user = User::create([
    'name' => 'Yehuda',
    'email' => 'yehuda@yiddishe-kop.com',
    'password' => '12345678'
  ]);
  $this->be($this->user);

  $this->cart->add($this->product);

  expect(Cart::get()->user_id)->toEqual($this->user->id);
})->only();
