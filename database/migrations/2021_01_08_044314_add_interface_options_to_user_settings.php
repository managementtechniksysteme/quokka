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
        Schema::table('user_settings', function (Blueprint $table) {
            $table->unsignedTinyInteger('list_pagination_size')->default(15);
            $table->boolean('show_finished_items')->default(false);
            $table->boolean('task_comments_sort_newest_first')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_settings', function (Blueprint $table) {
            $table->dropColumn('list_pagination_size');
            $table->dropColumn('show_finished_items');
            $table->dropColumn('task_comments_sort_newest_first');
        });
    }
}
