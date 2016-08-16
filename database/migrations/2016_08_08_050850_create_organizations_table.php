<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');// name of the organization
            $table->string('domain')->unique();
            $table->string('logo')->nullable();// url of the image/logo // or $table->binary('logo'); // blob of the logo
            $table->string('default_tz');// HQ time zone
            $table->json('alt_tz');// will be an array of timezones [{loc:US,tz:-8:00},{loc:UK,tz:+0:00}}]
            $table->integer('idle_time');
            $table->json('ip_lists');// {'IP addr' => 'a.b.c.d', 'status' => 'static/dynamic'} -> i.e. constant IP or varying IP
            $table->json('ip_status'); 
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
        Schema::drop('organizations');
    }
}
