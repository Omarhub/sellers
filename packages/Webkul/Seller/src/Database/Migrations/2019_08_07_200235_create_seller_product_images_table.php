<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSellerProductImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_product_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable();
            $table->string('path');

            $table->integer('seller_product_id')->unsigned();
            $table->foreign('seller_product_id')->references('id')->on('seller_products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seller_product_images');
    }
}
