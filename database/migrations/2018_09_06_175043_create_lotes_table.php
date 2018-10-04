<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lotes', function (Blueprint $table) {
            $table->unsignedInteger('id', true);

	    $table->string('num_lote', 9);
	    $table->string('num_oficio_ca', 16);
            $table->date('fecha_oficio_lote')->nullable();
            $table->string('ticket_msi', 15)->nullable();
            $table->string('status',1);
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('lotes');
    }
}
