<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRechazoIdAndValijaIdFkToSolicitudesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->foreign('valija_id')->references('id')->on('valijas');
            $table->foreign('lote_id')->references('id')->on('lotes');
            $table->foreign('rechazo_id')->references('id')->on('rechazos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->dropForeign('solicitudes_valija_id_foreign');
            $table->dropForeign('solicitudes_lote_id_foreign');
            $table->dropForeign('solicitudes_rechazo_id_foreign');
        });
    }
}
