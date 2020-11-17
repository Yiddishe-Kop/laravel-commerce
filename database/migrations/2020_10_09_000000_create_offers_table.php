<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use YiddisheKop\LaravelCommerce\Models\Offer;

class CreateOffersTable extends Migration {

  public function up() {
    Schema::create('offers', function (Blueprint $table) {

      $table->id();
      $table->string('name')->default('Special Offer');
      $table->string('type')->default(Offer::TYPE_PERCENTAGE);
      $table->integer('min')->default(1);
      $table->integer('max')->nullable();
      $table->integer('discount')->default(10);
      $table->string('product_type')->nullable();

      $table->timestamps();
    });
  }

  public function down() {
    Schema::dropIfExists('offers');
  }
}
