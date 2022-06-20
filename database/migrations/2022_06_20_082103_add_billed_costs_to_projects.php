<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBilledCostsToProjects extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('projects', 'billed_costs')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->double('billed_costs')->after('wage_costs')->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('projects', 'billed_costs')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropColumn('billed_costs');
            });
        }
    }
}
