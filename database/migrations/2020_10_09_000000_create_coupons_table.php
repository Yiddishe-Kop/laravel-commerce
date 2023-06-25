<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use YiddisheKop\LaravelCommerce\Models\Coupon;

return new class extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Coupon');
            $table->string('code')->unique();
            $table->string('type')->default(Coupon::TYPE_PERCENTAGE);

            $table->integer('max_uses')->nullable();
            $table->integer('times_used')->default(0);
            $table->integer('discount')->default(10);

            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_to')->nullable();

            $table->nullableMorphs('product');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
};
