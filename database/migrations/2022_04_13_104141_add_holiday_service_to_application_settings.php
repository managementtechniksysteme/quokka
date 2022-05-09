<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHolidayServiceToApplicationSettings extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('application_settings', 'holiday_service_id')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->unsignedBigInteger('holiday_service_id')->nullable();
                $table->foreign('holiday_service_id')->references('id')->on('services')->onDelete('set null')->onUpdate('cascade');
            });
        }

        if (!Schema::hasColumn('application_settings', 'holiday_yearly_allowance')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->unsignedTinyInteger('holiday_yearly_allowance')->nullable();
            });
        }
    }

    public function down()
    {

        if (Schema::hasColumn('application_settings', 'holiday_service_id')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->dropColumn('holiday_service_id');
            });
        }

        if (Schema::hasColumn('application_settings', 'holiday_yearly_allowance')) {
            Schema::table('application_settings', function (Blueprint $table) {
                $table->dropColumn('holiday_yearly_allowance');
            });
        }
    }
}
