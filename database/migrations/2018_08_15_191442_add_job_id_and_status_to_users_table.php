<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJobIdAndStatusToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('job_id')->after('delegacion_id');

            $table->foreign('job_id')->references('id')->on('jobs');
            $table->char('status', 1)->after('remember_token')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_job_id_foreign');
            $table->dropColumn('job_id');
            $table->dropColumn('status');
        });
    }
}
