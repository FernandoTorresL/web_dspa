<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultadoSolicitudesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resultado_solicitudes', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('resultado_lote_id');
            $table->unsignedInteger('solicitud_id');
            $table->string('cuenta', 8);
            $table->string('name', 20)->nullable();
            $table->unsignedInteger('grupo_id')->nullable();
            $table->char('instalation_data', 10)->nullable();
            $table->unsignedTinyInteger('rechazo_mainframe_id')->nullable();
            $table->char('status',1)->default(0);
            $table->string('comment')->nullable();
            $table->unsignedInteger('user_id');

            $table->timestamps();

            $table->foreign('resultado_lote_id')->references('id')->on('resultado_lotes');
            $table->foreign('solicitud_id')->references('id')->on('solicitudes');
            $table->foreign('grupo_id')->references('id')->on('groups');
            $table->foreign('rechazo_mainframe_id')->references('id')->on('rechazos_mainframe');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resultado_solicitudes');
    }
}
