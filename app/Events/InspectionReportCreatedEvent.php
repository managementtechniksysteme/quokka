<?php

namespace App\Events;

use App\Models\InspectionReport;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InspectionReportCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public InspectionReport $inspectionReport;
    public User $user;
    public bool $notifySelf;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(InspectionReport $inspectionReport, User $user, bool $notifySelf)
    {
        $this->inspectionReport = $inspectionReport;
        $this->user = $user;
        $this->notifySelf = $notifySelf;
    }
}
