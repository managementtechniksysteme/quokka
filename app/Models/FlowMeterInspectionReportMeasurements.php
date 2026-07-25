<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FlowMeterInspectionReportMeasurements extends Model
{
    protected function casts(): array
    {
        return [
        'q_percent' => 'int',
        'q_value' => 'double',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
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
    }

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
        return $field ? $field->format("Y-m-d\TH:i") : '';
    }

}
