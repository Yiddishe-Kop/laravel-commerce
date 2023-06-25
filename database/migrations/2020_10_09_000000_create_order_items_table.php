<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->morphs('model');
            $table->unsignedInteger('quantity')->default(1);
            $table->json('options')->nullable();
            $table->string('title');
            $table->integer('price');
            $table->integer('discount')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
