<?php

namespace YiddisheKop\LaravelCommerce\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use YiddisheKop\LaravelCommerce\Contracts\Purchasable as PurchasableContract;
use YiddisheKop\LaravelCommerce\Traits\Purchasable;

class Product extends Model implements PurchasableContract
{
    use Purchasable;

    protected $guarded = [];

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPrice($currency = null, $options = null): int
    {
        // this is just for testing
        $price = $this->price;

        if ($options) {
            $price += [
                'small' => 10,
                'medium' => 20,
                'large' => 30,
            ][$options['size']];
        }

        return ($price * ($currency == 'GBP' ? 0.5 : 1)) * 100;
    }
}
