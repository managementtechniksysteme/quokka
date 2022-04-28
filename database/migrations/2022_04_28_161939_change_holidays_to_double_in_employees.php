<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeHolidaysToDoubleInEmployees extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('employees', 'holidays')) {
            DB::statement('ALTER TABLE employees MODIFY holidays DOUBLE');
        }
    }

    public function down()
    {
        if (Schema::hasColumn('employees', 'holidays')) {
            Schema::table('employees', function (Blueprint $table) {
                DB::statement('ALTER TABLE employees MODIFY holidays TINYINT UNSIGNED');
            });
        }
    }
}
