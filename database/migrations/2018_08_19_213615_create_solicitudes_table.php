<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitudesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('valija_id')->nullable();
            $table->date('fecha_solicitud_del');
            $table->unsignedInteger('lote_id')->nullable();
            $table->unsignedTinyInteger('delegacion_id');
            $table->unsignedTinyInteger('subdelegacion_id')->nullable();
            $table->string('nombre', 32);
            $table->string('primer_apellido', 32);
            $table->string('segundo_apellido', 32);
            $table->char('matricula', 10)->nullable();
            $table->string('curp', 18)->nullable();
            $table->string('cuenta', 7);
            $table->unsignedTinyInteger('movimiento_id');
            $table->unsignedInteger('gpo_nuevo_id')->nullable();
            $table->unsignedInteger('gpo_actual_id')->nullable();
            $table->string('comment')->nullable();
            $table->unsignedTinyInteger('rechazo_id')->nullable();
            $table->string('archivo')->nullable();
            $table->unsignedInteger('user_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitudes');
    }
}
