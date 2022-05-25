<?php

namespace App\Events;

use App\Models\InspectionReport;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InspectionReportSignedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public InspectionReport $inspectionReport;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(InspectionReport $inspectionReport)
    {
        $this->inspectionReport = $inspectionReport;
    }
}
