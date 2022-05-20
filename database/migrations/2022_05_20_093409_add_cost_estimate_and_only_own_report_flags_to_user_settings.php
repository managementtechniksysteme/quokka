<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCostEstimateAndOnlyOwnReportFlagsToUserSettings extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('user_settings', 'show_only_own_reports')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->boolean('show_only_own_reports')->default(true);
            });
        }
        if (!Schema::hasColumn('user_settings', 'show_cost_estimates')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->boolean('show_cost_estimates')->default(true);
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('user_settings', 'show_only_own_reports')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->dropColumn('show_only_own_reports');
            });
        }
        if (Schema::hasColumn('user_settings', 'show_cost_estimates')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->dropColumn('show_cost_estimates');
            });
        }
    }
}
