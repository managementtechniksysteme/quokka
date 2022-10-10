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
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;

class FlowMeterInspectionReportMeasurements extends Model
{
    protected $casts = [
        'q_percent' => 'int',
        'q_value' => 'double',
        'started_at' => 'datetime:H:i',
        'ended_at' => 'datetime:H:i',
        'measurement_transformer_reading_start' => 'double',
        'measurement_transformer_reading_end' => 'double',
        'measurement_transformer_reading_sum' => 'double',
        'pcs_reading_start' => 'double',
        'pcs_reading_end' => 'double',
        'pcs_reading_sum' => 'double',
        'comparison_measurement_start' => 'double',
        'comparison_measurement_end' => 'double',
        'comparison_measurement_sum' => 'double',
        'measurement_difference' => 'double',
        'q_value_average_mobile' => 'double'
    ];

    protected $fillable = [
        'q_percent',
        'q_value',
        'started_at',
        'ended_at',
        'measurement_transformer_reading_start',
        'measurement_transformer_reading_end',
        'measurement_transformer_reading_sum',
        'pcs_reading_start',
        'pcs_reading_end',
        'pcs_reading_sum',
        'comparison_measurement_start',
        'comparison_measurement_end',
        'comparison_measurement_sum',
        'measurement_difference',
        'q_value_average_mobile',
        'flow_meter_inspection_report_id'
    ];

    protected $touches = [
        'flowMeterInspectionReport'
    ];

    public function flowMeterInspectionReport()
    {
        return $this->belongsTo(FlowMeterInspectionReport::class);
    }

    public function getStartedAtForInputFieldAttribute() {
        return $this->getTimeStringForInputField($this->started_at);
    }

    public function getEndedAtForInputFieldAttribute() {
        return $this->getTimeStringForInputField($this->ended_at);
    }

    private function getTimeStringForInputField(Carbon $field) {
        return $field ? $field->format("H:i") : '';
    }

}