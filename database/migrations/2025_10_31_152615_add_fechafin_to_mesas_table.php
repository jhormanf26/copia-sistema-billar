<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFechafinToMesasTable extends Migration
{
    public function up()
    {
        Schema::table('mesas', function (Blueprint $table) {
            $table->dateTime('fechafin')->nullable();
        });
    }

    public function down()
    {
        Schema::table('mesas', function (Blueprint $table) {
            $table->dropColumn('fechafin');
        });
    }
};
