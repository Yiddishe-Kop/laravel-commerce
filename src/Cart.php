<?php

namespace YiddisheKop\LaravelCommerce;

use Illuminate\Support\Traits\ForwardsCalls;
use YiddisheKop\LaravelCommerce\Models\OrderItem;
use YiddisheKop\LaravelCommerce\Traits\SessionCart;
use YiddisheKop\LaravelCommerce\Models\Order as OrderModel;
use YiddisheKop\LaravelCommerce\Contracts\Order as OrderContract;

class Cart
{
    use SessionCart;
    use ForwardsCalls;

    protected $user;

    public function __construct($user = null)
    {
        $this->user = auth()->id();
    }

    public function get(): OrderContract
    {
        $this->user = auth()->id();

        if ($this->user) {
            if ($cart = config('commerce.models.order', OrderModel::class)::whereStatus(OrderModel::STATUS_CART)
                ->where('user_id', $this->user)
                ->with('items')
                ->first()
            ) {
                return $cart;
            }
        }

        return $this->getOrMakeSessionCart();
    }

    public function find($id): OrderContract
    {
        $order = config('commerce.models.order', OrderModel::class)::isCart()
            ->with('items')
            ->find($id);

        if (! $order) {
            return $this->refreshSessionCart();
        }

        if ($this->user && ! $order->user_id) {
            $order->update([
                'user_id' => $this->user,
            ]);
        }

        return $order;
    }

    public function create($attributes = [])
    {
        return config('commerce.models.order', OrderModel::class)::create($attributes);
    }

    public function transferGuestCartItemsToUserCart($userId)
    {
        $cartId = session()->get('cart');
        $guestCart = OrderModel::isCart()
            ->withCount('items')
            ->with('items.model')
            ->find($cartId);
        $userOldCart = OrderModel::where('user_id', $userId)
            ->isCart()
            ->withCount('items')
            ->first();
        if ($guestCart && $guestCart->items_count) {
            if ($userOldCart) {
                $guestCart->items->each(function (OrderItem $item) use ($userOldCart) {
                    $userOldCart->add($item->model, $item->quantity, $item->options);
                });
                $guestCart->delete(); // triggers orderItems deletion
            } else {
                $guestCart->update([
                    'user_id' => $userId,
                ]);
            }
        }
    }

    /**
     * Pass dynamic method calls to the Order.
     */
    public function __call($method, $arguments)
    {
        return $this->forwardCallTo($this->get(), $method, $arguments);
    }
}
