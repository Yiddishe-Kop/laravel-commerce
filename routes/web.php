<?php

use Illuminate\Support\Facades\Route;
use YiddisheKop\LaravelCommerce\Http\Controllers\CartItemController;

Route::post('cart', [CartItemController::class, 'store'])->name('cart.add');
