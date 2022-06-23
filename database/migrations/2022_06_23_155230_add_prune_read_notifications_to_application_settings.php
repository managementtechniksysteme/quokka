<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasColumn('application_settings', 'prune_read_notifications')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->boolean('prune_read_notifications')->default(false);
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('application_settings', 'prune_read_notifications')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->dropColumn('prune_read_notifications');
            });
        }
    }
};
