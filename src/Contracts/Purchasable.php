<?php

namespace YiddisheKop\LaravelCommerce\Contracts;

interface Purchasable
{

    /**
     * Get the title of the product
     */
    public function getTitle(): string;

    /**
     *  Get the price for a single product
     *
     *  [!] return the price in cents
     *
     *  @return int price in cents
     */
    public function getPrice($currency = null, $options = null): int;
}
