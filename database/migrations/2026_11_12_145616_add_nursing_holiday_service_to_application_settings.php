<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Pflegeurlaub (nursing holiday) is a special kind of time off, configured as a
 * wage service like the regular holiday service.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_settings', function (Blueprint $table) {
            $table->foreignId('nursing_holiday_service_id')->nullable()->after('holiday_yearly_allowance')
                ->constrained('services')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('application_settings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('nursing_holiday_service_id');
        });
    }
};
