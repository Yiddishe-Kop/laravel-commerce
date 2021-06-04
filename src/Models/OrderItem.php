<?php

namespace YiddisheKop\LaravelCommerce\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'options' => 'array'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function model()
    {
        return $this->morphTo();
    }
}
