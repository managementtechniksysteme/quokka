<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeHolidaysToDoubleInEmployees extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('employees', 'holidays')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->double('holidays')->change();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('employees', 'holidays')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->unsignedTinyInteger('holidays')->change();
            });
        }
    }
}
