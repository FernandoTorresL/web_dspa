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

            $table->string('cuenta', 12);
            $table->unsignedInteger('inventory_id');
            $table->unsignedTinyInteger('ciz_id');
            $table->unsignedTinyInteger('delegacion_id');
            $table->unsignedInteger('gpo_owner_id');
            $table->unsignedInteger('gpo_default_id');
            $table->unsignedInteger('work_area_id')->nullable();
            $table->string('name', 32);
            $table->date('created');
            $table->date('passdate')->nullable();
            $table->string('passint', 5);
            $table->string('attribute', 50);
            $table->dateTime('last_access')->nullable();
            $table->string('install_data', 100);
            $table->string('model', 16);
            $table->string('comment')->nullable();

            $table->timestamps();

            $table->index(['inventory_id', 'cuenta', 'ciz_id', 'delegacion_id'], 'idx_detalle_ctas');
            $table->index(['cuenta'], 'idx_detalle_ctas_cuenta');
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
