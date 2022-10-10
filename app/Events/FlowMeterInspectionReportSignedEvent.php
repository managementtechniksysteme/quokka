<?php

namespace App\Events;

use App\Models\FlowMeterInspectionReport;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FlowMeterInspectionReportSignedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public FlowMeterInspectionReport $flowMeterInspectionReport;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(FlowMeterInspectionReport $flowMeterInspectionReport)
    {
        $this->flowMeterInspectionReport = $flowMeterInspectionReport;
    }
}
