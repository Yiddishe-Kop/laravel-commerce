<?php

use Illuminate\Support\Facades\Route;
use YiddisheKop\LaravelCommerce\Http\Controllers\CartItemController;
use YiddisheKop\LaravelCommerce\Http\Controllers\OrderCompleteController;
use YiddisheKop\LaravelCommerce\Http\Controllers\CheckoutController;
use YiddisheKop\LaravelCommerce\Http\Controllers\WebhookController;

// Route::post('cart', [CartItemController::class, 'store'])->name('cart.add');
// Route::delete('cart/{item}', [CartItemController::class, 'destroy'])->name('cart.remove');

Route::get('order/{order}/pay', CheckoutController::class)->name('order.pay');
Route::get('order/{order}/complete', OrderCompleteController::class)->name('order.complete');

Route::get('webhook', WebhookController::class)->name('order.webhook');
