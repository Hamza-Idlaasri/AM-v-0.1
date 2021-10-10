<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneNumberToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('users', function (Blueprint $table) {
            $table->string('phone_number')->default(0);
        });
    }


    public function down()
    {
        Schema::connection('mysql2')->table('users', function (Blueprint $table) {
            $table->dropColumn('phone_number');
        });
    }
}
