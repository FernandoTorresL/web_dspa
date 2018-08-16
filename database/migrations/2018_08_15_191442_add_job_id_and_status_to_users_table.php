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
            $table->tinyInteger('job_id')->after('delegacion_id')->unsigned();
            $table->foreign('job_id')->references('id')->on('jobs');
            $table->char('status', 1)->after('job_id')->default(0);
//            $table->timestamps()->after('status');
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
