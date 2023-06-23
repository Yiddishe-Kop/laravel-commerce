<?php

namespace YiddisheKop\LaravelCommerce\Models;

use Illuminate\Database\Eloquent\Model;
use YiddisheKop\LaravelCommerce\Events\RemovedFromCart;

class OrderItem extends Model
{
    protected $dispatchesEvents = [
        'deleted'      => RemovedFromCart::class,
        'trashed'      => RemovedFromCart::class,
        'forceDeleted' => RemovedFromCart::class,
    ];

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'options' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(config('commerce.models.order', Order::class));
    }

    public function model()
    {
        return $this->morphTo();
    }

    public function getLineTotalAttribute()
    {
        return ($this->price - $this->discount) * $this->quantity;
    }
}
