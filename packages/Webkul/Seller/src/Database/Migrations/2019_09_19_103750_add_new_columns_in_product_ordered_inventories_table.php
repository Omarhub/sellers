<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsInProductOrderedInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_ordered_inventories', function (Blueprint $table) {

            $table->dropForeign('product_ordered_inventories_product_id_foreign');

            $table->dropForeign('product_ordered_inventories_channel_id_foreign');

            $table->dropUnique('product_ordered_inventories_product_id_channel_id_unique');

            $table->integer('vendor_id')->nullable();

            $table->unique(['product_id', 'channel_id', 'vendor_id'], 'pd_or_in_pd_ch_vd_id_unique');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_ordered_inventories', function (Blueprint $table) {
            //
        });
    }
}
