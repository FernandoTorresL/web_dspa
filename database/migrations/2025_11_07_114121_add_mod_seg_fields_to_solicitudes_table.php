<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModSegFieldsToSolicitudesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->unsignedTinyInteger('rol_id')->after('user_id')->default(null)->nullable();
            $table->char('telefono', 10)->after('rol_id')->default(null)->nullable();
            $table->char('tel_ext', 5)->after('telefono')->default(null)->nullable();
            $table->string('email', 50)->after('tel_ext')->default(null)->nullable();
            $table->string('nombre_pc', 50)->after('email')->default(null)->nullable();
            $table->char('dir_ip', 15)->after('nombre_pc')->default(null)->nullable();
            $table->char('mac_address', 17)->after('dir_ip')->default(null)->nullable();
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
            $table->dropColumn('rol_id');
            $table->dropColumn('telefono');
            $table->dropColumn('tel_ext');
            $table->dropColumn('email');
            $table->dropColumn('nombre_pc');
            $table->dropColumn('dir_ip');
            $table->dropColumn('mac_address');
        });
    }
}
