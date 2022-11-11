<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasColumn('application_settings', 'kilometre_costs')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->unsignedDouble('kilometre_costs')->nullable()->default(null);
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('application_settings', 'kilometre_costs')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->dropColumn('kilometre_costs');
            });
        }
    }
};
