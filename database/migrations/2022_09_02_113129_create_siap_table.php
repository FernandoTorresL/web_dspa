<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siap', function (Blueprint $table) {
            $table->increments('id');

            $table->char('matricula', 10);
            $table->unsignedTinyInteger('delegacion_id');
            $table->string('primer_apellido', 35);
            $table->string('segundo_apellido', 35)->nullable();
            $table->string('nombre', 35);
            $table->string('cve_adscripcion', 15)->nullable();
            $table->string('adscripcion', 50)->nullable();
            $table->string('cve_puesto', 10);
            $table->string('puesto', 40);

            $table->unsignedInteger('catalogo_id');
            $table->string('comment')->nullable();
            $table->timestamps();

            $table->index(['matricula'], 'idx_siap');
            $table->index(['catalogo_id'], 'idx_siap_catalogo_id');
            $table->index(['matricula', 'delegacion_id'], 'idx_siap_mat_del');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siap');
    }
}
