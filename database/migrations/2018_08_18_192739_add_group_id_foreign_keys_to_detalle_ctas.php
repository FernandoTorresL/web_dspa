<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGroupIdForeignKeysToDetalleCtas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detalle_ctas', function (Blueprint $table) {
            $table->foreign('gpo_owner_id')->references('id')->on('groups');
            $table->foreign('gpo_default_id')->references('id')->on('groups');
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
            $table->dropForeign('detalle_ctas_gpo_owner_id_foreign');
            $table->dropForeign('detalle_ctas_gpo_default_id_foreign');
        });
    }
}
