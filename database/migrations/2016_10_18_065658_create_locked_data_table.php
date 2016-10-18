<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLockedDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locked__datas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();// ID of the member/user
            $table->date('work_date');// day the user was working
            $table->time('start_time')->nullable();// from what time user started working
            $table->time('end_time')->nullable();// till what time user was working
            $table->string('total_time');
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
        Schema::drop('locked__datas');
    }
}
