<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultadoLotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resultado_lotes', function (Blueprint $table) {
            $table->unsignedInteger('id', true);

            $table->unsignedInteger('lote_id');
            $table->dateTime('attended_at');
            $table->string('comment')->nullable();
            $table->unsignedInteger('user_id');
            $table->string('file')->nullable();

            $table->timestamps();

            $table->foreign('lote_id')->references('id')->on('lotes');
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
        Schema::dropIfExists('resultado_lotes');
    }
}
