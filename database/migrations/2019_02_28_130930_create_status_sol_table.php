<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusSolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_sol', function (Blueprint $table) {
            $table->unsignedTinyInteger('id', true);
            $table->string('name',50);
            $table->string('description');
            $table->unsignedInteger('work_area_id')->nullable();

            $table->foreign('work_area_id')->references('id')->on('work_areas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status_sol');
    }
}
