<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountingExpandErrorsToUserSettings extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('user_settings', 'accounting_expand_errors')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->boolean('accounting_expand_errors')->default(true);
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('user_settings', 'accounting_expand_errors')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->dropColumn('accounting_expand_errors');
            });
        }
    }
}
