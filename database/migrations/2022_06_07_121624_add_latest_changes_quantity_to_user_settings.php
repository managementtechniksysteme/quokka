<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLatestChangesQuantityToUserSettings extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('user_settings', 'latest_changes_quantity')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->unsignedTinyInteger('latest_changes_quantity')->default(50);
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('user_settings', 'show_only_own_reports')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->dropColumn('latest_changes_quantity');
            });
        }
    }
}
