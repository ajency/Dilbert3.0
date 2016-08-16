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
            $table->date('work_date');
            $table->time('cos');//change of state
            $table->integer('user_id');// ID of the member/user
            $table->string('from_state');//state before change
            $table->string('to_state');//state after change
            $table->string('ip_addr');// IP address of the system
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
