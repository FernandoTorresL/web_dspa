<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleCtasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_ctas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inventario_id');
            $table->string('cuenta', 12);
            $table->unsignedTinyInteger('ciz_id');
            $table->unsignedTinyInteger('delegacion_id');
            $table->unsignedInteger('gpo_owner_id');
            $table->unsignedInteger('gpo_default_id');
            $table->unsignedInteger('area_id');
            $table->string('name', 32);
            $table->date('created');
            $table->date('passdate')->nullable();
            $table->string('passint', 5);
            $table->string('attribute', 50);
            $table->dateTime('last_access')->nullable();
            $table->string('install_data', 100);
            $table->string('model', 16);
            $table->string('comment', 256)->nullable();
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
        Schema::dropIfExists('detalle_ctas');
    }
}
