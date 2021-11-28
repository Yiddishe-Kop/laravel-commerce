# Changelog

All notable changes to this project will be documented in this file. See [standard-version](https://github.com/conventional-changelog/standard-version) for commit guidelines.

### 1.0.2 (2021-11-28)

- New Feature: Restrict a coupon to a specific product!

We added the following columns to the `coupons` table: `product_type` & `product_id`.

If you're upgrading an existing installation, create the following migration:

```php
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use YiddisheKop\LaravelCommerce\Models\Coupon;

class AddProductMorphsToCouponsTable extends Migration
{

    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->nullableMorphs('product');
        });
    }

    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropMorphs('product');
        });
    }
}

```

### [1.0.1-alpha.0](https://github.com/Yiddishe-Kop/laravel-commerce/compare/v1.0.0...v1.0.1-alpha.0) (2020-11-30)

## 1.0.0 (2020-11-30)

- initial release 🥳

### Features

* coupons ([31f0099](https://github.com/Yiddishe-Kop/laravel-commerce/commit/31f00994bc7b386473b8257ee630918d22b01e53))
* Offer start/expiry dates ([610cfe5](https://github.com/Yiddishe-Kop/laravel-commerce/commit/610cfe519d7eafb5f5e0e19bf636467ad131de3e))
* order appends timeAgo ([2c58c51](https://github.com/Yiddishe-Kop/laravel-commerce/commit/2c58c513c457d4acb809254add1874494a239fce))
* order->timeAgo accessor ([c190a7d](https://github.com/Yiddishe-Kop/laravel-commerce/commit/c190a7d28a082f50c0c276544e9dbcc27ec7f7e3))
* OrderCompleted event ([3b6a18d](https://github.com/Yiddishe-Kop/laravel-commerce/commit/3b6a18d829df754d108db50a961b675bab5ac2d9))
* removeCoupon ([41a3681](https://github.com/Yiddishe-Kop/laravel-commerce/commit/41a3681aba7a87ce7d2f95e06cd2684fc60f50d9))
* shipping, product options ([0278a93](https://github.com/Yiddishe-Kop/laravel-commerce/commit/0278a935542fedb8f9b2943d8783db18009762c3))
* special Offers (wip) ([ba75d46](https://github.com/Yiddishe-Kop/laravel-commerce/commit/ba75d4636eec2aa0e4c6e393a628eb3c545d26aa))


### Bug Fixes

* eventServiceProvider ([fbff95e](https://github.com/Yiddishe-Kop/laravel-commerce/commit/fbff95e64a2d79781a2a54499ea7842415847d73))
* Order paid_at dates ([02a09a7](https://github.com/Yiddishe-Kop/laravel-commerce/commit/02a09a73bf1551a7e850a79b1056296706091376))
* pass attributes at cart creation ([3291bea](https://github.com/Yiddishe-Kop/laravel-commerce/commit/3291bea836ce512fa8e9461a4d537560cd6826c1))
* recalculate totals after adding/removing item from cart ([db56afd](https://github.com/Yiddishe-Kop/laravel-commerce/commit/db56afdb4f78fcea226c0e86c99da9e30f91442e))
* remove offer when removing cart-items ([f96f1a8](https://github.com/Yiddishe-Kop/laravel-commerce/commit/f96f1a814992acd412a82d407c9649522c048c7c))
* reverted to coupon_total ([fdaa6d4](https://github.com/Yiddishe-Kop/laravel-commerce/commit/fdaa6d4a30225af000d77a95c18196a0036efc8d))
* set default currency at cart creation ([c650483](https://github.com/Yiddishe-Kop/laravel-commerce/commit/c6504832c8bc4835c2c31bd9db90a213426d6dc7))
