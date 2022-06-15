<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultSignedListElementFilterToUserSettings extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('user_settings', 'show_signed_reports')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->boolean('show_signed_reports')->default(false);
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('user_settings', 'show_signed_reports')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->dropColumn('show_signed_reports');
            });
        }
    }
}
