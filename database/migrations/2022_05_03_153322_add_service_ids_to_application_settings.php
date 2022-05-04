<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceIdsToApplicationSettings extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('application_settings', 'allowances_service_id')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->unsignedBigInteger('allowances_service_id');
            });
        }
        if (!Schema::hasColumn('application_settings', 'overtime_50_service_id')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->unsignedBigInteger('overtime_50_service_id');
            });
        }
        if (!Schema::hasColumn('application_settings', 'overtime_100_service_id')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->unsignedBigInteger('overtime_100_service_id');
            });
        }
        if (!Schema::hasColumn('application_settings', 'time_balance_service_id')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->unsignedBigInteger('time_balance_service_id');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('application_settings', 'allowances_service_id')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->dropColumn('allowances_service_id');
            });
        }
        if (Schema::hasColumn('application_settings', 'overtime_50_service_id')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->dropColumn('overtime_50_service_id');
            });
        }
        if (Schema::hasColumn('application_settings', 'overtime_100_service_id')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->dropColumn('overtime_100_service_id');
            });
        }
        if (Schema::hasColumn('application_settings', 'time_balance_service_id')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->dropColumn('time_balance_service_id');
            });
        }
    }
}
