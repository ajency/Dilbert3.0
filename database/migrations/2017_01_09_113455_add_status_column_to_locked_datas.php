<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusColumnToLockedDatas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locked__datas', function (Blueprint $table) {
            $table->string('status')->nullable();// absent, present, leave
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locked__datas', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
