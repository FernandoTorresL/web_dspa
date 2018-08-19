<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAreaIdForeignKeyToDetalleCtas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detalle_ctas', function (Blueprint $table) {
            $table->foreign('work_area_id')->references('id')->on('work_areas');
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
            $table->dropForeign('detalle_ctas_work_area_id_foreign');
        });
    }
}
