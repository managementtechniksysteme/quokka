<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountingFilterDefaultDaysToUserSettings extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('user_settings', 'accounting_filter_default_days')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->unsignedTinyInteger('accounting_filter_default_days')->nullable()->default(3);
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('user_settings', 'accounting_filter_default_days')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->dropColumn('accounting_filter_default_days');
            });
        }
    }
}
