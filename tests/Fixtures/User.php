<?php

namespace YiddisheKop\LaravelCommerce\Tests\Fixtures;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use YiddisheKop\LaravelCommerce\Traits\HasOrders;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthorizableContract, AuthenticatableContract {
  use HasOrders, Authorizable, Authenticatable;

  protected $table = 'users';

  protected $guarded = [];

}
