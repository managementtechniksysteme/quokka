<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEstimatedCostsToProjects extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('projects', 'material_costs')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->double('material_costs')->after('ends_on')->nullable();
            });
        }

        if (!Schema::hasColumn('projects', 'wage_costs')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->double('wage_costs')->after('material_costs')->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('projects', 'material_costs')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropColumn('material_costs');
            });
        }

        if (Schema::hasColumn('projects', 'wage_costs')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropColumn('wage_costs');
            });
        }
    }
}
