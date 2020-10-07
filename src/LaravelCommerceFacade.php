<?php

namespace YiddisheKop\LaravelCommerce;

use Illuminate\Support\Facades\Facade;

/**
 * @see \YiddisheKop\LaravelCommerce\Skeleton\SkeletonClass
 */
class LaravelCommerceFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-commerce';
    }
}
