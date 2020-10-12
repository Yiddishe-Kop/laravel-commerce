<?php

use YiddisheKop\LaravelCommerce\Exceptions\OrderNotAssignedToUser;
use YiddisheKop\LaravelCommerce\Models\Order;
use YiddisheKop\LaravelCommerce\Tests\Fixtures\Product;
use YiddisheKop\LaravelCommerce\Tests\Fixtures\User;

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

  $this->user = User::create([
    'name' => 'Yehuda',
    'email' => 'yehuda@yiddishe-kop.com',
    'password' => '12345678'
  ]);
});

it('throws an exception if no user assigned to order', function () {
  $this->expectException(OrderNotAssignedToUser::class);
  $this->cart->markAsCompleted();
});

it('marks the order as complete', function () {
  // dump($this->cart->attributesToArray());
  $this->cart->update([
    'user_id' => $this->user->id
  ]);
  $this->cart->markAsCompleted();
  $this->assertTrue($this->cart->is_paid);
  $this->assertEquals(Order::STATUS_COMPLETED, $this->cart->status);
  $this->assertTrue(today()->isSameDay($this->cart->paid_date));
});

test('user has orders relation', function() {
  $this->cart->update([
    'user_id' => $this->user->id
  ]);
  expect($this->user->orders()->get())->toHaveCount(0);
  $this->cart->markAsCompleted();
  expect($this->user->orders()->get())->toHaveCount(1);
});
