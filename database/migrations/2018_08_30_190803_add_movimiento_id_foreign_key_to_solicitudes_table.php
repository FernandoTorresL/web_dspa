<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMovimientoIdForeignKeyToSolicitudesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->foreign('movimiento_id')->references('id')->on('movimientos');
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
            $table->dropForeign('solicitudes_movimiento_id_foreign');
        });
    }
}
