<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsInCartAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart_address', function (Blueprint $table) {
            $table->string('area');
            $table->integer('block');
            $table->string('street')->nullable();
            $table->integer('avenue')->nullable();
            $table->string('building');
            $table->integer('floor')->nullable();
            $table->integer('flat')->nullable();
            $table->string('direction')->nullable();
            $table->dropColumn(['country', 'state', 'postcode']);
        });

        Schema::table('cart_address', function (Blueprint $table) {
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->integer('postcode')->nullable();
            $table->string('city')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
