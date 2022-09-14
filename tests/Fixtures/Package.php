<?php

namespace YiddisheKop\LaravelCommerce\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use YiddisheKop\LaravelCommerce\Traits\Purchasable;
use YiddisheKop\LaravelCommerce\Contracts\Purchasable as PurchasableContract;

class Package extends Model implements PurchasableContract
{
    use Purchasable;

    protected $guarded = [];

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPrice($currency = null, $options = null): int
    {
        return $this->price * 100;
    }
}
