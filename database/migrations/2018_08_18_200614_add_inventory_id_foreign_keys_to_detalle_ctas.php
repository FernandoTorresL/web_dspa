<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInventoryIdForeignKeysToDetalleCtas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detalle_ctas', function (Blueprint $table) {
            $table->foreign('inventory_id')->references('id')->on('inventories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detalle_ctas', function (Blueprint $table) {
            $table->dropForeign('detalle_ctas_inventory_id_foreign');
        });
    }
}
