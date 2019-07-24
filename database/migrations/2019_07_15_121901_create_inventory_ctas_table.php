<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryCtasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_ctas', function (Blueprint $table) {
            $table->increments('id');

            $table->string('cuenta', 12);
            $table->boolean('ciz_1')->default(false);
            $table->boolean('ciz_2')->default(false);;
            $table->boolean('ciz_3')->default(false);;
            $table->string('name', 32);
            $table->unsignedInteger('gpo_owner_id');
            $table->string('install_data', 100);
            $table->unsignedInteger('inventory_id');
            $table->unsignedTinyInteger('delegacion_id');
            $table->unsignedInteger('work_area_id')->nullable();
            $table->string('comment')->nullable();

            $table->timestamps();

            $table->foreign('gpo_owner_id')->references('id')->on('groups');
            $table->foreign('inventory_id')->references('id')->on('inventories');
            $table->foreign('delegacion_id')->references('id')->on('delegaciones');
            $table->foreign('work_area_id')->references('id')->on('work_areas');

            $table->unique(['cuenta', 'name', 'gpo_owner_id', 'install_data', 'inventory_id', 'delegacion_id', 'work_area_id'], 'idx_unique_inventory_ctas');
            $table->index(['cuenta'], 'idx_inventory_ctas');
            $table->index(['inventory_id', 'cuenta', 'delegacion_id'], 'idx_inventory_ctas_del');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_ctas');
    }
}
