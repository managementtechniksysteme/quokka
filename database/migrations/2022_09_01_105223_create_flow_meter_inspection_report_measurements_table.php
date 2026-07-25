<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlowMeterInspectionReportMeasurementsTable extends Migration {
    public function up()
    {
        Schema::create('flow_meter_inspection_report_measurements', function (Blueprint $table) {
            $table->id();

            // using an integer instead of enum for the fixed values from the report table allows for
            // arbitrary measurement points in the future
            $table->unsignedTinyInteger('q_percent');
            $table->double('q_value')->nullable();
            $table->datetime('started_at')->nullable();
            $table->datetime('ended_at')->nullable();
            $table->double('measurement_transformer_reading_start')->nullable();
            $table->double('measurement_transformer_reading_end')->nullable();
            $table->double('measurement_transformer_reading_sum')->nullable();
            $table->double('pcs_reading_start')->nullable();
            $table->double('pcs_reading_end')->nullable();
            $table->double('pcs_reading_sum')->nullable();
            $table->double('comparison_measurement_start')->nullable();
            $table->double('comparison_measurement_end')->nullable();
            $table->double('comparison_measurement_sum')->nullable();
            $table->double('measurement_difference')->nullable();
            $table->double('q_value_average_mobile')->nullable();

            $table->unsignedBigInteger('flow_meter_inspection_report_id');

            $table->timestamps();

            $table->unique(['q_percent', 'flow_meter_inspection_report_id'], 'flow_meter_inspection_report_measurements_unique');

            $table->foreign('flow_meter_inspection_report_id', 'flow_meter_inspection_report_measurements_report_id_foreign')->references('id')->on('flow_meter_inspection_reports')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('flow_meter_inspection_report_measurements');
    }
}
