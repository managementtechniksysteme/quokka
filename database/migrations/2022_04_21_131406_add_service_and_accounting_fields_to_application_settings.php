<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceAndAccountingFieldsToApplicationSettings extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('application_settings', 'currency_unit')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->string('currency_unit')->default('€');
            });
        }
        if (!Schema::hasColumn('application_settings', 'services_hour_unit')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->string('services_hour_unit')->nullable();
            });
        }
        if (!Schema::hasColumn('application_settings', 'accounting_min_amount')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->unsignedDouble('accounting_min_amount')->default(0.5);
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('application_settings', 'currency_unit')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->dropColumn('currency_unit');
            });
        }
        if (Schema::hasColumn('application_settings', 'services_hour_unit')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->dropColumn('services_hour_unit');
            });
        }
        if (Schema::hasColumn('application_settings', 'accounting_min_amount')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->dropColumn('accounting_min_amount');
            });
        }
    }
}
