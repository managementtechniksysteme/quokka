<?php

namespace App\Models;

use App\Support\GlobalSearch\FiltersGlobalSearch;
use App\Support\GlobalSearch\GlobalSearchResult;
use App\Traits\FiltersPermissions;
use App\Traits\FiltersSearch;
use App\Traits\HasAttachmentsAndSignatureRequests;
use App\Traits\HasDownloadRequest;
use App\Traits\OrdersResults;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;

class InspectionReport extends Model implements FiltersGlobalSearch, HasMedia
{
    use FiltersSearch;
    use FiltersPermissions;
    use HasAttachmentsAndSignatureRequests;
    use HasDownloadRequest;
    use OrdersResults;

    protected $casts = [
        'inspected_on' => 'date',
        'uvc_lamp_quantity' => 'int',
        'uvc_lamp_operating_hours' => 'int',
        'uvc_lamp_impulses' => 'int',
        'uvc_lamp_uv_intensity_arrival' => 'double',
        'uvc_lamp_uv_intensity_departure' => 'double',
        'uvc_lamp_replacement_available' => 'bool',
        'uvc_sensor_pre_alarm' => 'double',
        'uvc_sensor_cut_off_point' => 'double',
        'quartz_tube_contaminated' => 'bool',
        'quartz_tube_leaking' => 'bool',
        'water_suspended_load_visible' => 'bool',
        'water_air_bubble_free' => 'bool',
        'water_flow_rate' => 'double',
        'water_minimum_uv_transmission' => 'double',
        'water_measured_uv_transmission' => 'double',
    ];

    protected $fillable = [
        'status',
        'inspected_on',
        'weather',
        'equipment_type',
        'equipment_identifier',
        'uvc_lamp_type',
        'uvc_lamp_quantity',
        'uvc_lamp_operating_hours',
        'uvc_lamp_impulses',
        'uvc_lamp_uv_intensity_arrival',
        'uvc_lamp_uv_intensity_departure',
        'uvc_lamp_values_unit',
        'uvc_lamp_replacement_available',
        'uvc_sensor_type',
        'uvc_sensor_identifier',
        'uvc_sensor_pre_alarm',
        'uvc_sensor_cut_off_point',
        'uvc_sensor_values_unit',
        'quartz_tube_contaminated',
        'quartz_tube_leaking',
        'water_suspended_load_visible',
        'water_air_bubble_free',
        'water_flow_rate',
        'water_minimum_uv_transmission',
        'water_measured_uv_transmission',
        'comment',
        'project_id',
        'employee_id',
    ];

    protected $filterFields = [
        'equipment_identifier', 'comment', 'project.name', 'project.company.name',
    ];

    protected $filterKeys = [
        'ist:neu' => ['status', 'new'],
        'ist:unterschrieben' => ['status', 'signed'],
        'ist:u' => ['status', 'signed'],
        'ist:erledigt' => ['status', 'finished'],
        'projekt:(.*)' => ['project.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'p:(.*)' => ['project.name', '%{value}%', 'LIKE', 'NOT LIKE'],
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
        'inspection-reports.view.own' => ['employee.person_id', '{user}'],
        'inspection-reports.view.other' => ['!employee.person_id', '{user}'],
    ];

    public static function defaultFilter() : ?string
    {
        $filter = '';

        if (Auth::user()->settings->show_only_own_reports) {
            $filter .= 't:' . Auth::user()->username . ' ';
        }
        if (! Auth::user()->settings->show_finished_items) {
            $filter .= '!ist:erledigt ';
        }

        $filter = trim($filter);

        return $filter === '' ? null : $filter;
    }

    public static function filterGlobalSearch(string $query) : Collection
    {
        return InspectionReport::filterPermissions()
            ->filterSearch($query)
            ->with('project')
            ->get()
            ->map(function(InspectionReport $inspectionReport) {
                return new GlobalSearchResult(
                    InspectionReport::class,
                    'Prüfbericht',
                    $inspectionReport->id,
                    "Anlage $inspectionReport->equipment_identifier (Projekt {$inspectionReport->project->name}) vom $inspectionReport->inspected_on",
                    route('inspection-reports.show', $inspectionReport)
                );
            });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('signature')->singleFile()->useDisk('local');
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

    public function getUvcLampValuesUnitStringAttribute(): string
    {
        return $this->getUnitString($this->uvc_lamp_values_unit);
    }

    public function getUvcSensorValuesUnitStringAttribute(): string
    {
        return $this->getUnitString($this->uvc_sensor_values_unit);
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

    public function getUvcLampReplacementAvailableStringAttribute(): string
    {
        return $this->getBooleanString($this->uvc_lamp_replacement_available);
    }

    public function getQuartzTubeContaminatedStringAttribute(): string
    {
        return $this->getBooleanString($this->quartz_tube_contaminated);
    }

    public function getQuartzTubeLeakingStringAttribute(): string
    {
        return $this->getBooleanString($this->quartz_tube_leaking);
    }

    public function getWaterSuspendedLoadVisibleStringAttribute(): string
    {
        return $this->getBooleanString($this->water_suspended_load_visible);
    }

    public function getWaterAirBubbleFreeStringAttribute(): string
    {
        return $this->getBooleanString($this->water_air_bubble_free);
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

    public static function newInspectionReports()
    {
        return InspectionReport::whereStatus('new')->count();
    }

    public static function mtdSignedInspectionReports()
    {
        $now = Carbon::now();
        $firstOfMonth = Carbon::today()->firstOfMonth();

        return InspectionReport::whereStatus('signed')
            ->whereHas('media', function ($signature) use($firstOfMonth, $now) {
                return $signature
                    ->where('collection_name', 'signature')
                    ->whereBetween('created_at', [$firstOfMonth, $now]);
            })
            ->count();
    }

    public static function signedInspectionReports()
    {
        return InspectionReport::whereStatus('signed')->count();
    }
}
