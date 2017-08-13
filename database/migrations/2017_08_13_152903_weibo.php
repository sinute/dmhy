<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Weibo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weibo', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fansub_id');
            $table->string('app_key', 32);
            $table->string('app_secret', 32);
            $table->string('access_token', 32);
            $table->unsignedInteger('last_id');
            $table->unsignedInteger('created_at');
            $table->unsignedInteger('updated_at');
            $table->unsignedInteger('deleted_at');
            $table->unique('fansub_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('weibo');
    }
}
