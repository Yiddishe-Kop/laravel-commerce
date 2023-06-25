<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use YiddisheKop\LaravelCommerce\Models\Offer;

return new class extends Migration
{
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Special Offer');
            $table->string('type')->default(Offer::TYPE_PERCENTAGE);
            $table->integer('min')->default(1);
            $table->integer('discount')->default(10);
            $table->string('product_type')->nullable();

            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_to')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('offers');
    }
};
