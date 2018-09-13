<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistSolicitudesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hist_solicitudes', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('solicitud_id');
            $table->unsignedInteger('valija_id')->nullable();
            $table->date('fecha_solicitud_del');
            $table->unsignedInteger('lote_id')->nullable();
            $table->unsignedTinyInteger('delegacion_id');
            $table->unsignedTinyInteger('subdelegacion_id')->nullable();
            $table->string('nombre', 32);
            $table->string('primer_apellido', 32);
            $table->string('segundo_apellido', 32);
            $table->char('matricula', 10)->nullable();
            $table->string('curp', 20)->nullable();
            $table->string('cuenta', 7);
            $table->unsignedTinyInteger('movimiento_id');
            $table->unsignedInteger('gpo_nuevo_id')->nullable();
            $table->unsignedInteger('gpo_actual_id')->nullable();
            $table->string('comment')->nullable();
            $table->unsignedTinyInteger('rechazo_id')->nullable();
            $table->string('archivo')->nullable();
            $table->unsignedInteger('user_id');

            $table->timestamps();

            $table->foreign('solicitud_id')->references('id')->on('solicitudes');
            $table->foreign('valija_id')->references('id')->on('valijas');
            $table->foreign('lote_id')->references('id')->on('lotes');
            $table->foreign('delegacion_id')->references('id')->on('delegaciones');
            $table->foreign('subdelegacion_id')->references('id')->on('subdelegaciones');
            $table->foreign('movimiento_id')->references('id')->on('movimientos');
            $table->foreign('gpo_nuevo_id')->references('id')->on('groups');
            $table->foreign('gpo_actual_id')->references('id')->on('groups');
            $table->foreign('rechazo_id')->references('id')->on('rechazos');
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
        Schema::dropIfExists('hist_solicitudes');
    }
}
