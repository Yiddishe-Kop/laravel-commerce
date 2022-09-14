<?php

namespace YiddisheKop\LaravelCommerce;

use Illuminate\Support\Str;

class Gateway
{
    protected static $gateways = [];

    public static function bootGateways()
    {
        static::$gateways = [];

        foreach (config('commerce.gateways') as $class => $config) {
            if ($class) {
                $class = str_replace('::class', '', $class);

                static::$gateways[] = [
                    $class,
                    $config,
                ];
            }
        }

        return new static();
    }

    public static function gateways()
    {
        return collect(static::$gateways)
            ->map(function ($gateway) {
                $instance = new $gateway[0]();

                return [
                    'name'            => $instance->name(),
                    'handle'          => Str::camel($instance->name()),
                    'class'           => $gateway[0],
                    'formatted_class' => addslashes($gateway[0]),
                    'gateway-config'  => $gateway[1],
                ];
            })
            ->toArray();
    }
}
