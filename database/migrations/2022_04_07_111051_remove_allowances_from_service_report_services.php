<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveAllowancesFromServiceReportServices extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('service_report_services', 'allowances')) {
            Schema::table('service_report_services', function (Blueprint $table) {
                $table->dropColumn('allowances');
            });
        }
    }

    public function down()
    {
        Schema::table('service_report_services', function (Blueprint $table) {
            $table->unsignedDouble('allowances')->after('hours');
        });
    }
}
