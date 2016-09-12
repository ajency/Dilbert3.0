<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('avatar');
            $table->string('lang');// language, user wants the app & site to be displayed as
            $table->string('timeZone'); // user choses the time zone which is defined in company list -> also determines the region where user works
            $table->date('acd');//account creation date
            $table->unsignedInteger('org_id')->nullable();
            $table->string('socket_id')->nullable();
            $table->string('role')->nullable();
            $table->string('api_token', 60) ->nullable(); // for authenticating request from chrome app
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
