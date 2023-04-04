<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBilledFinancialCostsToProjects extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('projects', 'billed_financial_costs')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->unsignedDouble('billed_financial_costs')->nullable()->default(null);
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('projects', 'billed_financial_costs')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropColumn('billed_financial_costs');
            });
        }
    }
}