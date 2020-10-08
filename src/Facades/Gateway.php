<?php

namespace YiddisheKop\LaravelCommerce\Facades;

use Illuminate\Support\Facades\Facade;

class Gateway extends Facade {

  protected static function getFacadeAccessor() {
    return 'gateway';
  }

}
