<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration {

  public function up() {
    Schema::create('packages', function (Blueprint $table) {

      $table->id();
      $table->string('title');
      $table->unsignedInteger('price');
      $table->timestamps();

    });
  }

  public function down() {
    Schema::dropIfExists('packages');
  }

}
