<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistValijasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hist_valijas', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('valija_id');
            $table->unsignedTinyInteger('origen_id')->default(0);
            $table->string('status',1)->default(0);
            $table->string('num_oficio_ca', 32);
            $table->date('fecha_recepcion_ca')->nullable();
            $table->unsignedTinyInteger('delegacion_id');
            $table->string('num_oficio_del', 32);
            $table->date('fecha_valija_del')->nullable();
            $table->unsignedTinyInteger('rechazo_id')->nullable();
            $table->string('comment', 500)->nullable();
            $table->string('archivo')->nullable();
            $table->unsignedInteger('user_id');

            $table->timestamps();

            $table->foreign('valija_id')->references('id')->on('valijas');
            $table->foreign('delegacion_id')->references('id')->on('delegaciones');
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
        Schema::dropIfExists('hist_valijas');
    }
}
