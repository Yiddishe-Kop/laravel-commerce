# A simple commerce package for Laravel

<!-- [![Latest Version on Packagist](https://img.shields.io/packagist/v/yiddishe-kop/laravel-commerce.svg?style=flat-square)](https://packagist.org/packages/yiddishe-kop/laravel-commerce)
[![Total Downloads](https://img.shields.io/packagist/dt/yiddishe-kop/laravel-commerce.svg?style=flat-square)](https://packagist.org/packages/yiddishe-kop/laravel-commerce) -->

After searching for a simple ecommerce package for Laravel and not finding a lightweight simple to use solution - I decided to attempt to create one myself.

Read the official documentation here: https://laravel-commerce.yiddishe-kop.com/

### Features

- [x] Cart (stored in the session - so guests can also have a cart)
- [ ] Orders
- [ ] Coupons
- [ ] Special Offers
- [ ] Multiple Currencies
- [ ] Multiple Payment Gateways

This package only implements the backend logic, and leaves you with full control over the frontend.

## Installation

You can install the package via composer:

```bash
composer require yiddishe-kop/laravel-commerce
```

To publish the `commerce.php` config file:
```bash
php artisan vendor:publish --provider="YiddisheKop\LaravelCommerce\CommerceServiceProvider" --tag="config"
```

You can also publish the migrations if you need to customize them:
```bash
php artisan vendor:publish --provider="YiddisheKop\LaravelCommerce\CommerceServiceProvider" --tag="migrations"
```


## Usage

### Cart

You can access the cart anywhere, regardless if the user is logged in or a guest, using the facade:

``` php
use YiddisheKop\LaravelCommerce\Facades\Cart;

$cart = Cart::get();
```

When the guest logs in, the cart will be attached to his account 👌.

**Note**: If you want the cart to still be available after logout, you need to override the following method in `Auth\LoginController`:
```php
public function logout(Request $request) {
    $this->guard()->logout();

    // keep cart data for after logout
    $cartId = session()->get('cart');
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    session()->put('cart', $cartId);

    if ($response = $this->loggedOut($request)) {
        return $response;
    }

    return $request->wantsJson()
        ? new JsonResponse([], 204)
        : redirect('/');
}
```

### Products
You can make any model purchasable - by implementing the `Purchasable` contract:
```php
use YiddisheKop\LaravelCommerce\Contracts\Purchasable;
use YiddisheKop\LaravelCommerce\Traits\Purchasable as PurchasableTrait;

class Product implements Purchasable {
  use PurchasableTrait;

    // the title of the product
    public function getTitle(): string {
        return $this->name;
    }

    // the price
    public function getPrice(): int {
        return $this->price;
    }
}
```

##### Add products to cart
Adding a product to the cart couldn't be simpler:
```php
Cart::add(Purchasable $product, int $quantity = 1);
```
Alternatively:
```php
$product->addToCart($quantity = 1);
```
If you add a product that already exists in the cart, we'll automatically just update the quantity 😎 .

##### Remove products from the cart
```php
Cart::remove(Purchasable $product);
```
Alternatively:
```php
$product->removeFromCart();
```
To empty the whole cart:
```php
Cart::empty();
```
#### Access cart items
You can access the cart items using the `items` relation:
```php
$cartItems = $cart->items;
```
To access the Product model from the cartItem, use the `model` relation (morphable):
```php
$product = $cart->items[0]->model;
```
### Calculate Totals
To calculate and persist the totals of the cart, use the `calculateTotals()` method:
```php
Cart::calculateTotals();
```
Now the cart has the following data up to date:
```
[
  "items_total" => 3552
  "tax_total" => 710.0
  "coupon_total" => "0"
  "grand_total" => 4262.0
]
```
Deleted products will automatically get removed from the cart upon calculating the totals.

## Orders
You can use the `HasOrders` trait on the User model, to get a `orders` relationship:
```php

use YiddisheKop\LaravelCommerce\Traits\HasOrders;

class User {
  use HasOrders;
  // ...
}

// you can now get all the users' orders (status complete)
$orders = $user->orders;
```

### Testing
This package has extensive tests - with the delightful Pest framework. To run the tests:
``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email yehuda@yiddishe-kop.com instead of using the issue tracker.

## Credits

- [Yehuda Neufeld](https://github.com/yiddishe-kop)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
