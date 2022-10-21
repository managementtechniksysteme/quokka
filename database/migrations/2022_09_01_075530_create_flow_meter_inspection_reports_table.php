<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlowMeterInspectionReportsTable extends Migration {
    public function up()
    {
        Schema::create('flow_meter_inspection_reports', function (Blueprint $table) {
            $table->id();

            $table->enum('status', ['new', 'signed', 'finished']);
            $table->date('inspected_on');
            $table->enum('weather', ['sunny', 'cloudy', 'rainy', 'snowy']);
            $table->tinyInteger('temperature');
            $table->string('equipment_identifier');
            $table->string('area_1')->nullable();
            $table->string('area_2')->nullable();
            $table->string('area_3')->nullable();
            $table->unsignedMediumInteger('treatment_plant_size')->nullable();
            $table->string('measuring_point');
            $table->string('installation_point');
            $table->string('medium');
            $table->unsignedInteger('commissioning_year')->nullable();
            $table->string('responsible_person');
            $table->date('responsible_person_instructed_on');
            $table->string('instructor');
            $table->string('information_providing_people')->nullable();
            $table->date('last_inspected_on')->nullable();
            $table->string('last_inspected_by')->nullable();
            $table->string('last_inspection_project')->nullable();
            $table->unsignedDouble('profile_outer_diameter');
            $table->unsignedDouble('profile_wall_thickness');
            $table->string('profile_material');
            $table->boolean('without_cross_section_reduction');
            $table->boolean('fully_filled');
            $table->enum('speed_measurement_type', ['doppler_ultrasonic', 'ultrasonic_signal_transmit_time', 'ultrasonic_cross_correlation', 'radar', 'other']);
            $table->string('speed_measurement_type_other')->nullable();
            $table->string('water_level_measurement_type')->nullable();
            $table->string('equipment_changes')->nullable();
            $table->boolean('documentation_existent');
            $table->boolean('inspection_book_existent');
            $table->boolean('inspection_requirements_existent');
            $table->boolean('documentation_current');
            $table->string('equipment_changes_to_documentation')->nullable();
            $table->string('measuring_pipe_type')->nullable();
            $table->unsignedDouble('measuring_pipe_minimum_speed')->nullable();
            $table->enum('measuring_pipe_minimum_speed_unit', ['m_s'])->nullable();
            $table->unsignedDouble('measuring_pipe_maximum_speed')->nullable();
            $table->enum('measuring_pipe_maximum_speed_unit', ['m_s'])->nullable();
            $table->unsignedDouble('measuring_pipe_maximum_flow_rate')->nullable();
            $table->enum('measuring_pipe_maximum_flow_rate_unit', ['l_s', 'm3_h'])->nullable();
            $table->unsignedDouble('mucus_suppression')->nullable();
            $table->enum('mucus_suppression_unit', ['percent', 'l_s'])->nullable();
            $table->unsignedDouble('q_min');
            $table->unsignedDouble('q_max');
            $table->enum('flow_range_type', ['guess', 'statistical_analysis']);
            $table->string('water_level_meter')->nullable();
            $table->string('water_level_meter_make')->nullable();
            $table->string('water_level_meter_type')->nullable();
            $table->string('water_level_meter_identifier')->nullable();
            $table->string('flow_rate_meter');
            $table->string('flow_rate_meter_make');
            $table->string('flow_rate_meter_type');
            $table->string('flow_rate_meter_identifier');
            $table->enum('measurement_transformer_point', ['local', 'control_stand']);
            $table->string('measurement_transformer_make');
            $table->string('measurement_transformer_type');
            $table->string('measurement_transformer_identifier');
            $table->unsignedDouble('measurement_transformer_minimum_level')->nullable();
            $table->unsignedDouble('measurement_transformer_maximum_level')->nullable();
            $table->enum('measurement_transformer_level_unit', ['mA', 'V', 'interface']);
            $table->unsignedDouble('measurement_transformer_range_100_percent');
            $table->unsignedTinyInteger('measurement_transformer_impulses');
            $table->string('measurement_transformer_data_logging');
            $table->unsignedSmallInteger('headwater_pipe_diameter');
            $table->string('headwater_calming_section');
            $table->string('headwater_calming_section_assessment');
            $table->unsignedDouble('measurement_section_slope')->nullable();
            $table->string('measurement_section_slope_assessment_type')->nullable();
            $table->boolean('measurement_section_installation_according_to_manufacturer');
            $table->unsignedDouble('measurement_section_minimum_speed_undercut_point')->nullable();
            $table->unsignedSmallInteger('measurement_section_pipe_diameter');
            $table->boolean('measurement_section_access_possible')->nullable();
            $table->boolean('measurement_section_pipe_required_fill_level_existent')->nullable();
            $table->boolean('measurement_section_pipe_visible_inspection_inside_possible')->nullable();
            $table->string('measurement_section_pipe_visible_inspection_inside')->nullable();
            $table->boolean('measurement_section_pipe_contaminated')->nullable();
            $table->boolean('measurement_section_pipe_cleaning_possible')->nullable();
            $table->date('measurement_section_pipe_last_cleaned_on')->nullable();
            $table->boolean('measurement_section_sensor_cleaned')->nullable();
            $table->boolean('measurement_section_sensor_damaged')->nullable();
            $table->boolean('measurement_section_pipe_inside_surface_ok')->nullable();
            $table->boolean('measurement_section_pipe_grounding_existent')->nullable();
            $table->boolean('measurement_section_pipe_air_pockets_visible')->nullable();
            $table->unsignedSmallInteger('tailwater_pipe_diameter');
            $table->boolean('tailwater_pipe_fully_filled');
            $table->unsignedDouble('tailwater_runout_section_slope')->nullable();
            $table->string('tailwater_runout_section_slope_assessment_type')->nullable();
            $table->string('tailwater_runout_section_assessment');
            $table->boolean('tailwater_measurement_pipe_can_run_dry');
            $table->boolean('tailwater_flow_conditions_influenced');
            $table->string('tailwater_flow_conditions_influencer')->nullable();
            $table->string('zero_flow_rate_testing_conditions')->nullable();
            $table->string('zero_flow_rate_reading_points')->nullable();
            $table->unsignedDouble('zero_flow_rate_displayed_flow')->nullable();
            $table->enum('comparison_measurements_process', ['mobile_measurement_equipment', 'volumetric']);
            $table->enum('comparison_measurement_mobile_type', ['doppler_ultrasonic', 'ultrasonic_signal_transmit_time', 'ultrasonic_cross_correlation', 'radar', 'other'])->nullable();
            $table->string('comparison_measurement_mobile_type_other')->nullable();
            $table->string('comparison_measurement_mobile_installation_point')->nullable();
            $table->string('comparison_measurement_mobile_equipment_make')->nullable();
            $table->string('comparison_measurement_mobile_equipment_type')->nullable();
            $table->string('comparison_measurement_mobile_equipment_identifier')->nullable();
            $table->unsignedDouble('comparison_measurement_mobile_equipment_maximum_speed')->nullable();
            $table->enum('comparison_measurement_mobile_equipment_maximum_speed_unit', ['m_s'])->nullable();
            $table->unsignedDouble('comparison_measurement_mobile_equipment_maximum_flow_rate')->nullable();
            $table->enum('comparison_measurement_mobile_equipment_maximum_flow_rate_unit', ['l_s', 'm3_h'])->nullable();
            $table->unsignedDouble('comparison_measurement_mobile_equipment_q_min')->nullable();
            $table->date('comparison_measurement_mobile_equipment_last_calibrated_on')->nullable();
            $table->string('comparison_measurement_mobile_equipment_last_cal_provider')->nullable();
            $table->string('comparison_measurement_mobile_equipment_last_cal_doc_identifier')->nullable();
            $table->string('comparison_measurement_volumetric_basin')->nullable();
            $table->double('comparison_measurement_volumetric_basin_cross_section_area')->nullable();
            $table->string('comparison_measurement_volumetric_height_measurement_equipment')->nullable();
            $table->unsignedDouble('measurement_difference_up_to_30_q_max');
            $table->unsignedDouble('measurement_difference_above_30_q_max');
            $table->unsignedDouble('reading_difference_up_to_30_q_max');
            $table->unsignedDouble('reading_difference_above_30_q_max');
            $table->boolean('equipment_in_tolerance_range');
            $table->string('equipment_deficiencies')->nullable();
            $table->boolean('further_inspection_required')->nullable();
            $table->text('comment')->nullable();
            $table->string('appendix_description')->nullable();

            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('employee_id');

            $table->timestamps();

            $table->unique(['inspected_on', 'equipment_identifier', 'project_id'], 'flow_meter_inspection_reports_unique');

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('employee_id')->references('person_id')->on('employees')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('flow_meter_inspection_reports');
    }
}
