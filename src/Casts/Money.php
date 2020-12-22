<?php

namespace YiddisheKop\LaravelCommerce\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Money implements CastsAttributes
{

    public function get($model, $key, $value, $attributes)
    {
        return $value / 100;
    }

    public function set($model, $key, $value, $attributes)
    {
        return $value * 100;
    }

}
