<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEstimatedCostsToProjects extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->double('material_costs')->after('ends_on')->nullable();
            $table->double('wage_costs')->after('material_costs')->nullable();
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('material_costs');
            $table->dropColumn('wage_costs');
        });
    }
}
