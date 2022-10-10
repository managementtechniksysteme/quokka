<?php

namespace App\Events;

use App\Models\FlowMeterInspectionReport;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FlowMeterInspectionReportCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public FlowMeterInspectionReport $flowMeterInspectionReport;
    public User $user;
    public bool $notifySelf;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(FlowMeterInspectionReport $flowMeterInspectionReport, User $user, bool $notifySelf)
    {
        $this->flowMeterInspectionReport = $flowMeterInspectionReport;
        $this->user = $user;
        $this->notifySelf = $notifySelf;
    }
}
