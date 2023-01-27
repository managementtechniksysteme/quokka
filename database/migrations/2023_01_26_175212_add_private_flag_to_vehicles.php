<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrivateFlagToVehicles extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('vehicles', 'private')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->boolean('private')->default(false);
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('vehicles', 'private')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->dropColumn('private');
            });
        }
    }
}
