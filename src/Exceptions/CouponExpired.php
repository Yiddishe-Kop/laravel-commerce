<?php

namespace YiddisheKop\LaravelCommerce\Exceptions;

use Exception;

class CouponExpired extends Exception {

  protected string $message = 'CouponExpired';

}
