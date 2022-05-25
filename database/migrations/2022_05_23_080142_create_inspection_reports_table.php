<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInspectionReportsTable extends Migration
{
    public function up()
    {
        Schema::create('inspection_reports', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['new', 'signed', 'finished']);
            $table->date('inspected_on');
            $table->enum('weather', ['sunny', 'cloudy', 'rainy', 'snowy']);
            $table->string('equipment_type');
            $table->string('equipment_identifier');
            $table->string('uvc_lamp_type');
            $table->unsignedTinyInteger('uvc_lamp_quantity');
            $table->unsignedMediumInteger('uvc_lamp_operating_hours');
            $table->unsignedSmallInteger('uvc_lamp_impulses');
            $table->unsignedDouble('uvc_lamp_uv_intensity_arrival');
            $table->unsignedDouble('uvc_lamp_uv_intensity_departure');
            $table->enum('uvc_lamp_values_unit', ['percent', 'W_m2']);
            $table->boolean('uvc_lamp_replacement_available')->nullable();
            $table->string('uvc_sensor_type');
            $table->string('uvc_sensor_identifier');
            $table->unsignedDouble('uvc_sensor_pre_alarm');
            $table->unsignedDouble('uvc_sensor_cut_off_point');
            $table->enum('uvc_sensor_values_unit', ['percent', 'W_m2']);
            $table->boolean('quartz_tube_contaminated')->nullable();
            $table->boolean('quartz_tube_leaking')->nullable();
            $table->boolean('water_suspended_load_visible')->nullable();
            $table->boolean('water_air_bubble_free')->nullable();
            $table->unsignedDouble('water_flow_rate');
            $table->unsignedDouble('water_minimum_uv_transmission');
            $table->unsignedDouble('water_measured_uv_transmission');
            $table->text('comment');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('employee_id');
            $table->timestamps();

            $table->unique(['inspected_on', 'equipment_identifier', 'project_id'], 'inspection_reports_unique');

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('employee_id')->references('person_id')->on('employees')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inspection_reports');
    }
}
