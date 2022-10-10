<?php

namespace App\Models;

use App\Support\GlobalSearch\FiltersGlobalSearch;
use App\Support\GlobalSearch\GlobalSearchResult;
use App\Traits\FiltersLatestChanges;
use App\Traits\FiltersPermissions;
use App\Traits\FiltersSearch;
use App\Traits\HasAttachmentsAndSignatureRequests;
use App\Traits\HasDownloadRequest;
use App\Traits\OrdersResults;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;

class FlowMeterInspectionReport extends Model implements FiltersGlobalSearch, HasMedia
{
    use FiltersLatestChanges;
    use FiltersSearch;
    use FiltersPermissions;
    use HasAttachmentsAndSignatureRequests;
    use HasDownloadRequest;
    use LogsActivity;
    use OrdersResults;

    protected $casts = [
        'inspected_on' => 'date',
        'temperature' => 'int',
        'treatment_plant_size' => 'int',
        'commissioning_year' => 'int',
        'responsible_person_instructed_on' => 'date',
        'last_inspected_on' => 'date',
        'last_inspection_project_date' => 'date',
        'without_cross_section_reduction' => 'bool',
        'fully_filled' => 'bool',
        'documentation_existent' => 'bool',
        'inspection_book_existent' => 'bool',
        'inspection_requirements_existent' => 'bool',
        'documentation_current' => 'bool',
        'measuring_pipe_minimum_speed' => 'double',
        'measuring_pipe_maximum_speed' => 'double',
        'measuring_pipe_maximum_flow_rate' => 'double',
        'mucus_suppression' => 'double',
        'q_min' => 'double',
        'q_max' => 'double',
        'measurement_transformer_minimum_level' => 'double',
        'measurement_transformer_maximum_level' => 'double',
        'measurement_transformer_range_100_percent' => 'double',
        'measurement_transformer_impulses' => 'int',
        'headwater_pipe_diameter' => 'int',
        'measurement_section_slope' => 'double',
        'measurement_section_installation_according_to_manufacturer' => 'bool',
        'measurement_section_minimum_speed_undercut_point' => 'double',
        'measurement_section_pipe_diameter' => 'int',
        'measurement_section_access_possible' => 'bool',
        'measurement_section_pipe_required_fill_level_existent' => 'bool',
        'measurement_section_pipe_visible_inspection_inside_possible' => 'bool',
        'measurement_section_pipe_contaminated' => 'bool',
        'measurement_section_pipe_cleaning_possible' => 'bool',
        'measurement_section_pipe_last_cleaned_on' => 'date',
        'measurement_section_sensor_cleaned' => 'bool',
        'measurement_section_sensor_damaged' => 'bool',
        'measurement_section_pipe_inside_surface_ok' => 'bool',
        'measurement_section_pipe_grounding_existent' => 'bool',
        'measurement_section_pipe_air_pockets_visible' => 'bool',
        'tailwater_pipe_diameter' => 'int',
        'tailwater_pipe_fully_filled' => 'bool',
        'tailwater_runout_section_slope' => 'double',
        'tailwater_measurement_pipe_can_run_dry' => 'bool',
        'tailwater_flow_conditions_influenced' => 'bool',
        'zero_flow_rate_displayed_flow' => 'double',
        'comparison_measurement_mobile_equipment_maximum_speed' => 'double',
        'comparison_measurement_mobile_equipment_maximum_flow_rate' => 'double',
        'comparison_measurement_mobile_equipment_q_min' => 'double',
        'comparison_measurement_mobile_equipment_last_calibrated_on' => 'date',
        'comparison_measurement_volumetric_basin_cross_section_area' => 'double',
        'comparison_measurement_measurement_transformer_checked' => 'bool',
        'comparison_measurement_pcs_checked' => 'bool',
        'measurement_difference_up_to_30_q_max' => 'double',
        'measurement_difference_above_30_q_max' => 'double',
        'reading_difference_up_to_30_q_max' => 'double',
        'reading_difference_above_30_q_max' => 'double',
        'equipment_in_tolerance_range' => 'bool',
        'further_inspection_required' => 'bool'
    ];

    protected $fillable = [
        'status',
        'inspected_on',
        'weather',
        'temperature',
        'equipment_identifier',
        'treatment_plant',
        'sewage_plant',
        'indirect_induction',
        'treatment_plant_size',
        'measuring_point',
        'installation_point',
        'medium',
        'commissioning_year',
        'responsible_person',
        'responsible_person_instructed_on',
        'instructor',
        'information_providing_person',
        'last_inspected_on',
        'last_inspected_by',
        'last_inspection_project',
        'last_inspection_project_date',
        'profile_measurements',
        'without_cross_section_reduction',
        'fully_filled',
        'speed_measurement_type',
        'speed_measurement_type_other',
        'water_level_measurement_type',
        'equipment_changes',
        'documentation_existent',
        'inspection_book_existent',
        'inspection_requirements_existent',
        'documentation_current',
        'equipment_changes_to_documentation',
        'measuring_pipe_type',
        'measuring_pipe_minimum_speed',
        'measuring_pipe_minimum_speed_unit',
        'measuring_pipe_maximum_speed',
        'measuring_pipe_maximum_speed_unit',
        'measuring_pipe_maximum_flow_rate',
        'measuring_pipe_maximum_flow_rate_unit',
        'mucus_suppression',
        'mucus_suppression_unit',
        'q_min',
        'q_max',
        'flow_range_type',
        'water_level_meter',
        'water_level_meter_make',
        'water_level_meter_type',
        'water_level_meter_identifier',
        'flow_rate_meter',
        'flow_rate_meter_make',
        'flow_rate_meter_type',
        'flow_rate_meter_identifier',
        'measurement_transformer_point',
        'measurement_transformer_make',
        'measurement_transformer_type',
        'measurement_transformer_identifier',
        'measurement_transformer_minimum_level',
        'measurement_transformer_maximum_level',
        'measurement_transformer_level_unit',
        'measurement_transformer_range_100_percent',
        'measurement_transformer_impulses',
        'measurement_transformer_data_logging',
        'measurement_transformer_registering_device_make',
        'measurement_transformer_registering_device_type',
        'measurement_transformer_registering_device_identifier',
        'headwater_pipe_diameter',
        'headwater_calming_section',
        'headwater_calming_section_assessment',
        'measurement_section_slope',
        'measurement_section_slope_assessment_type',
        'measurement_section_installation_according_to_manufacturer',
        'measurement_section_minimum_speed_undercut_point',
        'measurement_section_pipe_diameter',
        'measurement_section_access_possible',
        'measurement_section_pipe_required_fill_level_existent',
        'measurement_section_pipe_visible_inspection_inside_possible',
        'measurement_section_pipe_visible_inspection_inside',
        'measurement_section_pipe_contaminated',
        'measurement_section_pipe_cleaning_possible',
        'measurement_section_pipe_last_cleaned_on',
        'measurement_section_sensor_cleaned',
        'measurement_section_sensor_damaged',
        'measurement_section_pipe_inside_surface_ok',
        'measurement_section_pipe_grounding_existent',
        'measurement_section_pipe_air_pockets_visible',
        'tailwater_pipe_diameter',
        'tailwater_pipe_fully_filled',
        'tailwater_runout_section_slope',
        'tailwater_runout_section_slope_assessment_type',
        'tailwater_runout_section_assessment',
        'tailwater_measurement_pipe_can_run_dry',
        'tailwater_flow_conditions_influenced',
        'tailwater_flow_conditions_influencer',
        'zero_flow_rate_testing_conditions',
        'zero_flow_rate_reading_points',
        'zero_flow_rate_displayed_flow',
        'comparison_measurements_process',
        'comparison_measurement_mobile_type',
        'comparison_measurement_mobile_type_other',
        'comparison_measurement_mobile_installation_point',
        'comparison_measurement_mobile_equipment_make',
        'comparison_measurement_mobile_equipment_type',
        'comparison_measurement_mobile_equipment_identifier',
        'comparison_measurement_mobile_equipment_maximum_speed',
        'comparison_measurement_mobile_equipment_maximum_speed_unit',
        'comparison_measurement_mobile_equipment_maximum_flow_rate',
        'comparison_measurement_mobile_equipment_maximum_flow_rate_unit',
        'comparison_measurement_mobile_equipment_q_min',
        'comparison_measurement_mobile_equipment_last_calibrated_on',
        'comparison_measurement_mobile_equipment_last_cal_provider',
        'comparison_measurement_mobile_equipment_last_cal_doc_identifier',
        'comparison_measurement_volumetric_basin',
        'comparison_measurement_volumetric_basin_cross_section_area',
        'comparison_measurement_volumetric_height_measurement_equipment',
        'comparison_measurement_measurement_transformer_checked',
        'comparison_measurement_pcs_checked',
        'measurement_difference_up_to_30_q_max',
        'measurement_difference_above_30_q_max',
        'reading_difference_up_to_30_q_max',
        'reading_difference_above_30_q_max',
        'equipment_in_tolerance_range',
        'equipment_deficiencies',
        'further_inspection_required',
        'comment',
        'appendix_description',
        'project_id',
        'employee_id'
    ];

    protected $filterFields = [
        'equipment_identifier',
        'measuring_point',
        'comment',
        'project.name',
        'project.company.name',
        'employee.person.first_name',
        'employee.person.last_name',
        'employee.user.username',
    ];

    protected $filterKeys = [
        'ist:neu' => ['status', 'new'],
        'ist:unterschrieben' => ['status', 'signed'],
        'ist:u' => ['status', 'signed'],
        'ist:erledigt' => ['status', 'finished'],
        'projekt:(.*)' => ['project.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'p:(.*)' => ['project.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'firma:(.*)' => ['project.company.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'f:(.*)' => ['project.company.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'techniker:(.*)' => ['employee.user.username', '{value}'],
        't:(.*)' => ['employee.user.username', '{value}'],
    ];

    protected $orderKeys = [
        'default' => ['inspected_on'],
        'inspected_on-asc' => ['inspected_on'],
        'inspected_on-desc' => [['inspected_on', 'desc']],
        'status-asc' => ['raw' => 'field(status, "new", "signed", "finished"), inspected_on'],
        'status-desc' => ['raw' => 'field(status, "finished", "signed", "new"), inspected_on'],
    ];

    protected $permissionFilters = [
        'flow-meter-inspection-reports.view.own' => ['employee.person_id', '{user}'],
        'flow-meter-inspection-reports.view.other' => ['!employee.person_id', '{user}'],
    ];

    protected static $recordEvents = ['updated'];

    public static function defaultFilter() : ?string
    {
        $filter = '';

        if (Auth::user()->settings->show_only_own_reports) {
            $filter .= 't:' . Auth::user()->username . ' ';
        }
        if (! Auth::user()->settings->show_signed_reports) {
            $filter .= '!ist:unterschrieben ';
        }
        if (! Auth::user()->settings->show_finished_items) {
            $filter .= '!ist:erledigt ';
        }

        $filter = trim($filter);

        return $filter === '' ? null : $filter;
    }

    public static function filterGlobalSearch(string $query, ?int $latestQuantity = null) : Collection
    {
        return FlowMeterInspectionReport::filterPermissions()
            ->filterSearch($query)
            ->with('project')
            ->when($latestQuantity && $latestQuantity > 0, function ($query) use ($latestQuantity) {
                return $query->latest('updated_at')->limit($latestQuantity);
            })
            ->get()
            ->map(function(FlowMeterInspectionReport $flowMeterInspectionReport) {
                return new GlobalSearchResult(
                    FlowMeterInspectionReport::class,
                    'Prüfbericht für Durchflussmesseinrichtungen',
                    $flowMeterInspectionReport->id,
                    "Anlage $flowMeterInspectionReport->equipment_identifier (Projekt {$flowMeterInspectionReport->project->name}) vom $flowMeterInspectionReport->inspected_on",
                    route('flow-meter-inspection-reports.show', $flowMeterInspectionReport),
                    $flowMeterInspectionReport->created_at,
                    $flowMeterInspectionReport->updated_at,
                );
            });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('signature')->singleFile()->useDisk('local');
        $this->addMediaCollection('appendix')->singleFile()->useDisk('local');
        $this->addMediaCollection('attachments')->useDisk('local');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'person_id');
    }

    public function appendix()
    {
        return $this->getFirstMedia('appendix');
    }

    public function measurements()
    {
        return $this->hasMany(FlowMeterInspectionReportMeasurements::class);
    }

    public function measurementsQ20()
    {
        return $this->hasOne(FlowMeterInspectionReportMeasurements::class)->where('q_percent', 20);
    }

    public function measurementsQ30()
    {
        return $this->hasOne(FlowMeterInspectionReportMeasurements::class)->where('q_percent', 30);
    }

    public function measurementsQ50()
    {
        return $this->hasOne(FlowMeterInspectionReportMeasurements::class)->where('q_percent', 50);
    }

    public function measurementsQ70()
    {
        return $this->hasOne(FlowMeterInspectionReportMeasurements::class)->where('q_percent', 70);
    }

    public function measurementsQ100()
    {
        return $this->hasOne(FlowMeterInspectionReportMeasurements::class)->where('q_percent', 100);
    }

    public function addAppendix(UploadedFile $appendix)
    {
        $this->addMedia($appendix)->usingFileName('appendix.pdf')->toMediaCollection('appendix');
    }

    public function deleteAppendix()
    {
        $appendix = $this->appendix();

        if ($appendix) {
            $appendix->delete();
        }
    }

    public function getWithoutCrossSectionReductionStringAttribute(): string
    {
        return $this->without_cross_section_reduction ? 'ohne Verengung' : 'mit Verengung';
    }

    public function getFullyFilledStringAttribute(): string
    {
        return $this->fully_filled ? 'vollgefüllt' : 'teilgefüllt';
    }

    public function getDocumentationExistentStringAttribute(): string
    {
        return $this->getBooleanString($this->documentation_existent);
    }

    public function getInspectionBookExistentStringAttribute(): string
    {
        return $this->getBooleanString($this->inspection_book_existent);
    }

    public function getInspectionRequirementsExistentStringAttribute(): string
    {
        return $this->getBooleanString($this->inspection_requirements_existent);
    }

    public function getDocumentationCurrentStringAttribute(): string
    {
        return $this->getBooleanString($this->documentation_current);
    }

    public function getFurtherInspectionRequiredStringAttribute(): string
    {
        return $this->getBooleanString($this->further_inspection_required);
    }

    public function getMeasurementSectionInstallationAccordingToManufacturerStringAttribute(): string
    {
        return $this->getBooleanString($this->measurement_section_installation_according_to_manufacturer);
    }

    public function getMeasurementSectionAccessPossibleStringAttribute(): string
    {
        return $this->getBooleanString($this->measurement_section_access_possible);
    }

    public function getMeasurementSectionPipeRequiredFillLevelExistentStringAttribute(): string
    {
        return $this->getBooleanString($this->measurement_section_pipe_required_fill_level_existent);
    }

    public function getMeasurementSectionPipeVisibleInspectionInsidePossibleStringAttribute(): string
    {
        return $this->getBooleanString($this->measurement_section_pipe_visible_inspection_inside_possible);
    }

    public function getMeasurementSectionPipeContaminatedStringAttribute(): string
    {
        return $this->getBooleanString($this->measurement_section_pipe_contaminated);
    }

    public function getMeasurementSectionPipeCleaningPossibleStringAttribute(): string
    {
        return $this->getBooleanString($this->measurement_section_pipe_cleaning_possible);
    }

    public function getMeasurementSectionSensorCleanedStringAttribute(): string
    {
        return $this->getBooleanString($this->measurement_section_sensor_cleaned);
    }

    public function getMeasurementSectionSensorDamagedStringAttribute(): string
    {
        return $this->getBooleanString($this->measurement_section_sensor_damaged);
    }

    public function getMeasurementSectionPipeInsideSurfaceOkStringAttribute(): string
    {
        return $this->getBooleanString($this->measurement_section_pipe_inside_surface_ok);
    }

    public function getMeasurementSectionPipeGroundingExistentStringAttribute(): string
    {
        return $this->getBooleanString($this->measurement_section_pipe_grounding_existent);
    }

    public function getMeasurementSectionPipeAirPocketsVisibleStringAttribute(): string
    {
        return $this->getBooleanString($this->measurement_section_pipe_air_pockets_visible);
    }

    public function getTailwaterPipeFullyfilledStringAttribute(): string
    {
        return $this->getBooleanString($this->tailwater_pipe_fully_filled);
    }

    public function getTailwaterMeasurementPipeCanRunDryStringAttribute(): string
    {
        return $this->getBooleanString($this->tailwater_measurement_pipe_can_run_dry);
    }

    private function getBooleanString(?bool $value): string
    {
        return match ($value) {
            true => 'ja',
            false => 'nein',
            null => 'keine Angabe',
            default => 'keine Angabe',
        };
    }

    public function getMeasuringPipeMinimumSpeedUnitStringAttribute(): string
    {
        return $this->getUnitString($this->measuring_pipe_minimum_speed_unit);
    }

    public function getMeasuringPipeMaximumFlowRateUnitStringAttribute(): string
    {
        return $this->getUnitString($this->measuring_pipe_maximum_flow_rate_unit);
    }

    public function getMeasuringPipeMaximumSpeedUnitStringAttribute(): string
    {
        return $this->getUnitString($this->measuring_pipe_maximum_speed_unit);
    }

    public function getMucusSupressionUnitStringAttribute(): string
    {
        return $this->getUnitString($this->mucus_suppression);
    }

    public function getMeasurementTransformerLevelUnitStringAttribute(): string
    {
        return $this->getUnitString($this->measurement_transformer_level_unit);
    }

    public function getComparisonMeasurementMobileEquipmentMaximumFlowRateUnitStringAttribute(): string
    {
        return $this->getUnitString($this->comparison_measurement_mobile_equipment_maximum_flow_rate_unit);
    }

    public function getComparisonMeasurementMobileEquipmentMaximumSpeedUnitStringAttribute(): string
    {
        return $this->getUnitString($this->comparison_measurement_mobile_equipment_maximum_speed_unit);
    }

    private function getUnitString(string $unit) : string
    {
        $unitString = trans($unit);

        return match (true) {
            strlen($unitString) === 1 => "{$unitString}",
            strlen($unitString) > 1 => " {$unitString}",
            default => " {$unitString}",
        };
    }

    public function isNew()
    {
        return $this->status === 'new';
    }

    public function isSigned()
    {
        return $this->status === 'signed';
    }

    public function isFinished()
    {
        return $this->status === 'finished';
    }

    public static function newFlowMeterInspectionReports()
    {
        return FlowMeterInspectionReport::whereStatus('new')->count();
    }

    public static function mtdSignedFlowMeterInspectionReports()
    {
        $now = Carbon::now();
        $firstOfMonth = Carbon::today()->firstOfMonth();

        return FlowMeterInspectionReport::whereStatus('signed')
            ->whereHas('media', function ($signature) use($firstOfMonth, $now) {
                return $signature
                    ->where('collection_name', 'signature')
                    ->whereBetween('created_at', [$firstOfMonth, $now]);
            })
            ->count();
    }

    public static function signedFlowMeterInspectionReports()
    {
        return FlowMeterInspectionReport::whereStatus('signed')->count();
    }
}