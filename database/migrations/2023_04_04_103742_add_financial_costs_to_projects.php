<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFinancialCostsToProjects extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('projects', 'financial_costs')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->unsignedDouble('financial_costs')->nullable()->default(null);
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('projects', 'financial_costs')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropColumn('financial_costs');
            });
        }
    }
}