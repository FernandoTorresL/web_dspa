<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTracksAudTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracks_aud', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedTinyInteger('type_aud_id');
            $table->unsignedTinyInteger('action_aud_id');
            $table->unsignedTinyInteger('operation_aud_id');
            $table->unsignedTinyInteger('table_aud_id')->default(null)->nullable();
            $table->unsignedInteger('table_pk')->default(null)->nullable();
            $table->unsignedInteger('ip_aud_id')->default(null)->nullable();
            $table->unsignedInteger('user_id')->default(null)->nullable();
            $table->string('information')->default(null)->nullable();
            $table->timestamps();

            $table->foreign('type_aud_id')->references('id')->on('types_aud');
            $table->foreign('action_aud_id')->references('id')->on('actions_aud');
            $table->foreign('operation_aud_id')->references('id')->on('operations_aud');
            $table->foreign('table_aud_id')->references('id')->on('tables_aud');
            $table->foreign('ip_aud_id')->references('id')->on('ips_aud');
            $table->foreign('user_id')->references('id')->on('users');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tracks_aud');
    }
}
