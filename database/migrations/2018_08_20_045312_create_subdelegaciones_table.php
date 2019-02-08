<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubdelegacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subdelegaciones', function (Blueprint $table) {
//            $table->increments('id');
            $table->unsignedTinyInteger('id', true);

            $table->string('name');
            $table->unsignedTinyInteger('delegacion_id');
            $table->unsignedTinyInteger('num_sub');
            $table->string('status', 1);

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
        Schema::dropIfExists('subdelegaciones');
    }
}
