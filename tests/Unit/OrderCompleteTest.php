<?php

use Illuminate\Support\Facades\Event;
use YiddisheKop\LaravelCommerce\Models\Order;
use YiddisheKop\LaravelCommerce\Tests\Fixtures\User;
use YiddisheKop\LaravelCommerce\Events\OrderCompleted;
use YiddisheKop\LaravelCommerce\Tests\Fixtures\Product;
use YiddisheKop\LaravelCommerce\Exceptions\OrderNotAssignedToUser;

beforeEach(function () {
    $this->cart
        ->add(Product::create([
            'title' => 'BA Ziporen',
            'price' => 333,
        ]), 2)
        ->add(Product::create([
            'title' => 'BA Vilna',
            'price' => 444,
        ]), 5);

    $this->user = User::create([
        'name'     => 'Yehuda',
        'email'    => 'yehuda@yiddishe-kop.com',
        'password' => '12345678',
    ]);
});

it('throws an exception if no user assigned to order', function () {
    $this->expectException(OrderNotAssignedToUser::class);
    $this->cart->markAsCompleted();
});

it('marks the order as complete', function () {
    // dump($this->cart->attributesToArray());
    $this->cart->update([
        'user_id' => $this->user->id,
    ]);
    $this->cart->markAsCompleted();
    $this->assertEquals(Order::STATUS_COMPLETED, $this->cart->status);
    $this->assertTrue(today()->isSameDay($this->cart->paid_at));
});

test('OrderCompeleted event is emitted', function () {
    Event::fake();

    $this->cart->update([
        'user_id' => $this->user->id,
    ]);
    $this->cart->markAsCompleted();

    Event::assertDispatched(OrderCompleted::class, function (OrderCompleted $event) {
        return $event->order->id == $this->cart->id;
    });
});

test('user has orders relation', function () {
    $this->cart->update([
        'user_id' => $this->user->id,
    ]);
    expect($this->user->orders()->get())->toHaveCount(0);
    $this->cart->markAsCompleted();
    Order::create([
        'user_id' => $this->user->id,
    ]);
    expect(Order::count())->toBe(2);
    expect($this->user->orders()->get())->toHaveCount(1);
});
