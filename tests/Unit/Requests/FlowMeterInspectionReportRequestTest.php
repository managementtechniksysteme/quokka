<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\FlowMeterInspectionReportStoreRequest;
use App\Http\Requests\FlowMeterInspectionReportUpdateRequest;

/**
 * FlowMeterInspectionReportStoreRequest/UpdateRequest::rules() has 89 fields
 * with several conditional branches (required fields shift based on
 * speed_measurement_type, measurement_transformer_level_unit,
 * comparison_measurements_process, etc.) - per the test-backfill plan's
 * backlog, only the "volumetric" / non-"interface" branch was ever exercised
 * by a feature test. These unit tests exercise every branch directly.
 */
function flowMeterRulesFor(string $requestClass, array $data): array
{
    return $requestClass::create('/flow-meter-inspection-reports', 'POST', $data)->rules();
}

test('speed_measurement_type "other" requires speed_measurement_type_other, otherwise it is prohibited', function () {
    $other = flowMeterRulesFor(FlowMeterInspectionReportStoreRequest::class, ['speed_measurement_type' => 'other']);
    $radar = flowMeterRulesFor(FlowMeterInspectionReportStoreRequest::class, ['speed_measurement_type' => 'radar']);

    expect($other['speed_measurement_type_other'])->toBe('required')
        ->and($radar['speed_measurement_type_other'])->toBe('prohibited|nullable');
});

test('an "interface" transformer level unit prohibits min/max level, otherwise they are required', function () {
    $interface = flowMeterRulesFor(FlowMeterInspectionReportStoreRequest::class, ['measurement_transformer_level_unit' => 'interface']);
    $mA = flowMeterRulesFor(FlowMeterInspectionReportStoreRequest::class, ['measurement_transformer_level_unit' => 'mA']);

    expect($interface['measurement_transformer_minimum_level'])->toBe('prohibited|nullable')
        ->and($interface['measurement_transformer_maximum_level'])->toBe('prohibited|nullable')
        ->and($mA['measurement_transformer_minimum_level'])->toBe('required|numeric|min:0|lt:measurement_transformer_maximum_level')
        ->and($mA['measurement_transformer_maximum_level'])->toBe('required|numeric|min:0|gt:measurement_transformer_minimum_level');
});

test('a truthy measurement_section_slope requires an assessment type, otherwise it is prohibited', function () {
    $withSlope = flowMeterRulesFor(FlowMeterInspectionReportStoreRequest::class, ['measurement_section_slope' => 1.5]);
    $withoutSlope = flowMeterRulesFor(FlowMeterInspectionReportStoreRequest::class, []);

    expect($withSlope['measurement_section_slope_assessment_type'])->toBe('required')
        ->and($withoutSlope['measurement_section_slope_assessment_type'])->toBe('prohibited|nullable');
});

test('measurement_section_pipe_visible_inspection_inside_possible "0" requires the follow-up field', function () {
    $no = flowMeterRulesFor(FlowMeterInspectionReportStoreRequest::class, ['measurement_section_pipe_visible_inspection_inside_possible' => '0']);
    $yes = flowMeterRulesFor(FlowMeterInspectionReportStoreRequest::class, ['measurement_section_pipe_visible_inspection_inside_possible' => '1']);

    expect($no['measurement_section_pipe_visible_inspection_inside'])->toBe('required')
        ->and($yes['measurement_section_pipe_visible_inspection_inside'])->toBe('prohibited|nullable');
});

test('a truthy tailwater_runout_section_slope requires an assessment type, otherwise it is prohibited', function () {
    $withSlope = flowMeterRulesFor(FlowMeterInspectionReportStoreRequest::class, ['tailwater_runout_section_slope' => 1.5]);
    $withoutSlope = flowMeterRulesFor(FlowMeterInspectionReportStoreRequest::class, []);

    expect($withSlope['tailwater_runout_section_slope_assessment_type'])->toBe('required')
        ->and($withoutSlope['tailwater_runout_section_slope_assessment_type'])->toBe('prohibited|nullable');
});

test('a truthy tailwater_flow_conditions_influenced requires the influencer field, otherwise it is prohibited', function () {
    $influenced = flowMeterRulesFor(FlowMeterInspectionReportStoreRequest::class, ['tailwater_flow_conditions_influenced' => true]);
    $notInfluenced = flowMeterRulesFor(FlowMeterInspectionReportStoreRequest::class, ['tailwater_flow_conditions_influenced' => false]);

    expect($influenced['tailwater_flow_conditions_influencer'])->toBe('required')
        ->and($notInfluenced['tailwater_flow_conditions_influencer'])->toBe('prohibited|nullable');
});

test('the volumetric comparison process requires the basin fields', function () {
    $rules = flowMeterRulesFor(FlowMeterInspectionReportStoreRequest::class, ['comparison_measurements_process' => 'volumetric']);

    expect($rules['comparison_measurement_volumetric_basin'])->toBe('required')
        ->and($rules['comparison_measurement_volumetric_basin_cross_section_area'])->toBe('required')
        ->and($rules['comparison_measurement_volumetric_height_measurement_equipment'])->toBe('required')
        ->and($rules['comparison_measurement_mobile_type'])->toBe('nullable');
});

test('the mobile comparison process requires the mobile equipment fields', function () {
    $rules = flowMeterRulesFor(FlowMeterInspectionReportStoreRequest::class, [
        'comparison_measurements_process' => 'mobile_measurement_equipment',
        'comparison_measurement_mobile_type' => 'radar',
    ]);

    expect($rules['comparison_measurement_mobile_type'])->toBe('required|in:doppler_ultrasonic,ultrasonic_signal_transmit_time,ultrasonic_cross_correlation,radar,other')
        ->and($rules['comparison_measurement_mobile_installation_point'])->toBe('required')
        ->and($rules['comparison_measurement_mobile_equipment_last_calibrated_on'])->toBe('required|date')
        ->and($rules['comparison_measurement_volumetric_basin'])->toBe('nullable');
});

test('mobile comparison type "other" requires the free-text field, keyed off comparison_measurement_mobile_type itself', function () {
    // Regression: this used to read the unrelated speed_measurement_type input
    // instead of comparison_measurement_mobile_type, so selecting "other" here
    // while speed_measurement_type was anything else silently prohibited the
    // free-text field the user was supposed to fill in.
    $mobileOtherSpeedRadar = flowMeterRulesFor(FlowMeterInspectionReportStoreRequest::class, [
        'comparison_measurements_process' => 'mobile_measurement_equipment',
        'comparison_measurement_mobile_type' => 'other',
        'speed_measurement_type' => 'radar',
    ]);

    $mobileRadarSpeedOther = flowMeterRulesFor(FlowMeterInspectionReportStoreRequest::class, [
        'comparison_measurements_process' => 'mobile_measurement_equipment',
        'comparison_measurement_mobile_type' => 'radar',
        'speed_measurement_type' => 'other',
    ]);

    expect($mobileOtherSpeedRadar['comparison_measurement_mobile_type_other'])->toBe('required')
        ->and($mobileRadarSpeedOther['comparison_measurement_mobile_type_other'])->toBe('prohibited|nullable');
});

test('FlowMeterInspectionReportUpdateRequest requires measurements.100.comparison_measurement_sum, matching StoreRequest', function () {
    // Regression: UpdateRequest was missing this rule entirely (present on
    // StoreRequest, and on Update's own sibling fields measurement_transformer_
    // reading_sum/measurement_difference), silently making it optional on update.
    $rules = flowMeterRulesFor(FlowMeterInspectionReportUpdateRequest::class, []);

    expect($rules['measurements.100.comparison_measurement_sum'])->toBe('required|numeric|min:0');
});
