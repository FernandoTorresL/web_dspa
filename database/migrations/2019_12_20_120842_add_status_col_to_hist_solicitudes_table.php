<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusColToHistSolicitudesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hist_solicitudes', function (Blueprint $table) {
            $table->unsignedTinyInteger('status_sol_id')->after('comment')->default(null)->nullable();

            $table->foreign('status_sol_id')->references('id')->on('status_sol');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hist_solicitudes', function (Blueprint $table) {
            $table->dropForeign('hist_solicitudes_status_sol_id_foreign');

            $table->dropColumn('status_sol_id');
        });
    }
}
