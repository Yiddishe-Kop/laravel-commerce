<?php

namespace YiddisheKop\LaravelCommerce\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface Order
{
    public function items(): HasMany;

    public function user(): BelongsTo;

    public function setCurrency(string $currency): self;

    public function add(Purchasable $product, int $quantity = 1, array $options = null): self;

    public function remove(Purchasable $product): self;

    public function applyCoupon(string $code);

    public function removeCoupon();

    public function calculateTotals(): self;

    public function markAsCompleted(): self;
}
