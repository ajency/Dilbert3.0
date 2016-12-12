<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->increments('id');
             $table->string('email');
            $table->string('name');
            $table->string('issue');// Will contain a dropdown with list of common issues & other
            $table->string('issue_content')->nullable();// if user has chosen 'other', then this textbox is displayed & user has to type the issue
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
        Schema::drop('complaints');
    }
}
