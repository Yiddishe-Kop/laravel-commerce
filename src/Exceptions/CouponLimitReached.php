<?php

namespace YiddisheKop\LaravelCommerce\Exceptions;

use Exception;

class CouponLimitReached extends Exception {

  protected string $message = 'CouponLimitReached';

}
