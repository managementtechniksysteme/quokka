<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotifySelfToUserSettings extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('user_settings', 'notify_self')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->boolean('notify_self')->default(false);
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('user_settings', 'notify_self')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->dropColumn('notify_self');
            });
        }
    }
}
