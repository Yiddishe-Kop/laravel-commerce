<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use YiddisheKop\LaravelCommerce\Models\Order;

class CreateOrdersTable extends Migration {

  public function up() {
    Schema::create('orders', function (Blueprint $table) {

      $table->id();
      $table->foreignId('user_id')->nullable();
      $table->foreignId('coupon_id')->nullable();

      $table->string('status')->default(Order::STATUS_CART);
      $table->string('currency')->default(config('commerce.currency'));
      $table->timestamp('paid_at')->nullable();

      $table->integer('items_total')->default(0);
      $table->integer('tax_total')->default(0);
      $table->integer('coupon_total')->default(0);
      $table->integer('grand_total')->default(0);

      $table->string('gateway')->nullable();
      $table->json('gateway_data')->nullable();

      $table->timestamps();
    });
  }

  public function down() {
    Schema::dropIfExists('orders');
  }
}
