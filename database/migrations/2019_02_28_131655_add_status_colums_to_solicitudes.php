<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusColumsToSolicitudes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->unsignedTinyInteger('status_sol_id')->after('rechazo_id')->default(null)->nullable();
            //$table->unsignedTinyInteger('status_sol_id')->after('rechazo_id')->default(null);

            $table->integer('assigned_role_id')->after('status_sol_id')->default(null)->unsigned()->nullable();
            //$table->unsignedTinyInteger('assigned_role_id')->after('status_sol_id')->default(null);

            $table->string('comment_status')->after('assigned_role_id')->default(null)->nullable();

            $table->foreign('status_sol_id')->references('id')->on('status_sol');
            $table->foreign('assigned_role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->dropForeign('solicitudes_status_sol_id_foreign');
            $table->dropForeign('solicitudes_assigned_role_id_foreign');

            $table->dropColumn('status_sol_id');
            $table->dropColumn('assigned_role_id');
            $table->dropColumn('comment_status');
        });
    }
}
