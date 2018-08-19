<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDelegacionIdForeignKeyToDetalleCtas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detalle_ctas', function (Blueprint $table) {
            $table->foreign('delegacion_id')->references('id')->on('delegaciones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detalle_ctas', function (Blueprint $table) {
            $table->dropForeign('detalle_ctas_delegacion_id_foreign');
        });
    }
}
