<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDraftFlagToMemos extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('memos', 'draft')) {
            Schema::table('memos', function (Blueprint $table) {
                $table->boolean('draft')->default(false);
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('memos', 'draft')) {
            Schema::table('memos', function (Blueprint $table) {
                $table->dropColumn('draft');
            });
        }
    }
}
