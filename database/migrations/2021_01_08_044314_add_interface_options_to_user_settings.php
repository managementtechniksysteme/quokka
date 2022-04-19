<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInterfaceOptionsToUserSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('user_settings', 'list_pagination_size')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->unsignedTinyInteger('list_pagination_size')->default(15);
            });
        }
        if (!Schema::hasColumn('user_settings', 'show_finished_items')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->boolean('show_finished_items')->default(false);
            });
        }
        if (!Schema::hasColumn('user_settings', 'task_comments_sort_newest_first')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->boolean('task_comments_sort_newest_first')->default(true);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('user_settings', 'list_pagination_size')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->dropColumn('list_pagination_size');
            });
        }
        if (Schema::hasColumn('user_settings', 'show_finished_items')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->dropColumn('show_finished_items');
            });
        }
        if (Schema::hasColumn('user_settings', 'task_comments_sort_newest_first')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->dropColumn('task_comments_sort_newest_first');
            });
        }
    }
}
