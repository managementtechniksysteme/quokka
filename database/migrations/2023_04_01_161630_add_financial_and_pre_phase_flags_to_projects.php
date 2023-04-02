<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFinancialAndPrePhaseFlagsToProjects extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('projects', 'is_pre_execution')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->boolean('is_pre_execution')->default(false);
            });
        }

        if (!Schema::hasColumn('projects', 'include_in_finances')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->boolean('include_in_finances')->default(true);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('projects', 'is_pre_execution')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropColumn('is_pre_execution');
            });
        }

        if (Schema::hasColumn('projects', 'include_in_finances')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropColumn('include_in_finances');
            });
        }
    }
}
