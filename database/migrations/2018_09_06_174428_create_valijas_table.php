<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValijasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('valijas', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedTinyInteger('origen_id')->default(0);
            $table->string('num_oficio_ca', 32);
            $table->date('fecha_recepcion_ca');
            $table->unsignedTinyInteger('delegacion_id');
            $table->string('num_oficio_del', 32);
            $table->date('fecha_valija_del');
            $table->unsignedTinyInteger('rechazo_id')->default(0);
            $table->string('comment')->nullable();
            $table->string('archivo')->nullable();;
            $table->unsignedInteger('user_id');

            $table->timestamps();

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
        Schema::dropIfExists('valijas');
    }
}
