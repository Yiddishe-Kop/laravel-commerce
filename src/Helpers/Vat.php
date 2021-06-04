<?php

namespace YiddisheKop\LaravelCommerce\Helpers;

/*
|--------------------------------------------------------------------------
| VAT Helper
|--------------------------------------------------------------------------
|
| This class gives you static methods to get VAT out of prices with ease.
| For example, get VAT amount included in price, remove VAT to get the
| price before VAT and more goodies. Now create something amazing!
|
*/

/**
 * VAT Helper
 *
 * Built to work with cents (as it rounds the results to the nearest cent)
 */
class Vat
{
    /**
     * Get VAT from given price
     *
     * returns the VAT amount
     */
    public static function of(int $price)
    {
        return round($price / (1 + (1 / config('commerce.tax.rate'))), 4);
    }

    /**
     * Get VAT for given price
     *
     * returns the VAT amount
     */
    public static function for(int $price)
    {
        return round($price * config('commerce.tax.rate'), 4);
    }

    /**
     * Add VAT to given price
     *
     * returns price with VAT
     */
    public static function add(int $price)
    {
        return round($price + ($price * config('commerce.tax.rate')), 4);
    }

    /**
     * Remove VAT from given price
     *
     * returns price before VAT
     */
    public static function remove(int $price)
    {
        return round($price / (1 + config('commerce.tax.rate')), 4);
    }
}
