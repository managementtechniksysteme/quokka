<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBilledCostWarningToApplicationSettings extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('application_settings', 'project_billed_costs_warning_percentage')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->unsignedTinyInteger('project_billed_costs_warning_percentage')->nullable()->default(80);
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('application_settings', 'project_billed_costs_warning_percentage')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->dropColumn('project_billed_costs_warning_percentage');
            });
        }
    }
}
