<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->increments('id');
            $table->date('work_date')->nullable();
            $table->time('cos')->nullable();//change of state
            $table->unsignedInteger('user_id')->nullable();// ID of the member/user
            $table->string('from_state')->nullable();//state before change
            $table->string('to_state')->nullable();//state after change
            $table->string('ip_addr')->nullable();// IP address of the system
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
        Schema::drop('logs');
    }
}
