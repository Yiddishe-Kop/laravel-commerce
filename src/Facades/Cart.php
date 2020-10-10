<?php

namespace YiddisheKop\LaravelCommerce\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \YiddisheKop\LaravelCommerce\Cart
 */
class Cart extends Facade {

  protected static function getFacadeAccessor() {
    return 'cart';
  }

}
