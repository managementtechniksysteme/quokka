<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFlagToDeleteManualFinanceRecordsForFinishedProjectsToApplicationSettings extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('application_settings', 'remove_finished_project_finance_group')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->boolean('remove_finished_project_finance_group')->default(false);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('application_settings', 'remove_finished_project_finance_group')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->dropColumn('remove_finished_project_finance_group');
            });
        }
    }
}
