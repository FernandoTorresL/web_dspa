<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGposIdForeignKeyToSolicitudesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->foreign('gpo_nuevo_id')->references('id')->on('groups');
            $table->foreign('gpo_actual_id')->references('id')->on('groups');
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
            $table->dropForeign('solicitudes_gpo_nuevo_id_foreign');
            $table->dropForeign('solicitudes_gpo_actual_id_foreign');
        });
    }
}
