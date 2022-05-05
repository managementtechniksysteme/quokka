<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectCostWarningsToApplicationSettings extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('application_settings', 'project_material_costs_warning_percentage')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->unsignedTinyInteger('project_material_costs_warning_percentage')->nullable()->default(80);
            });
        }
        if (!Schema::hasColumn('application_settings', 'project_wage_costs_warning_percentage')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->unsignedTinyInteger('project_wage_costs_warning_percentage')->nullable()->default(80);
            });
        }
        if (!Schema::hasColumn('application_settings', 'project_overall_costs_warning_percentage')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->unsignedTinyInteger('project_overall_costs_warning_percentage')->nullable()->default(80);
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('application_settings', 'project_material_costs_warning_percentage')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->dropColumn('project_material_costs_warning_percentage');
            });
        }
        if (Schema::hasColumn('application_settings', 'project_wage_costs_warning_percentage')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->dropColumn('project_wage_costs_warning_percentage');
            });
        }
        if (Schema::hasColumn('application_settings', 'project_overall_costs_warning_percentage')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->dropColumn('project_overall_costs_warning_percentage');
            });
        }
    }
}
