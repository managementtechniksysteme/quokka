<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * spatie/laravel-activitylog v5 moved the tracked model changes ("attributes"/"old")
 * out of the `properties` column into a dedicated `attribute_changes` column. This
 * table predates v5, so we add the column and migrate the existing change data over
 * (per the package's UPGRADING guide) so historical activity entries keep their diffs.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->json('attribute_changes')->nullable()->after('properties');
        });

        DB::table('activity_log')->whereNotNull('properties')->eachById(function ($row) {
            $properties = json_decode($row->properties, true) ?: [];

            $changes = array_intersect_key($properties, array_flip(['attributes', 'old']));
            $remaining = array_diff_key($properties, array_flip(['attributes', 'old']));

            DB::table('activity_log')->where('id', $row->id)->update([
                'attribute_changes' => empty($changes) ? null : json_encode($changes),
                'properties' => empty($remaining) ? null : json_encode($remaining),
            ]);
        });
    }

    public function down(): void
    {
        // Fold the change data back into properties before dropping the column.
        DB::table('activity_log')->whereNotNull('attribute_changes')->eachById(function ($row) {
            $changes = json_decode($row->attribute_changes, true) ?: [];
            $properties = json_decode($row->properties, true) ?: [];

            DB::table('activity_log')->where('id', $row->id)->update([
                'properties' => json_encode(array_merge($properties, $changes)),
            ]);
        });

        Schema::table('activity_log', function (Blueprint $table) {
            $table->dropColumn('attribute_changes');
        });
    }
};
