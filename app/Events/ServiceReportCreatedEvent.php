<?php

namespace App\Events;

use App\Models\ServiceReport;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServiceReportCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ServiceReport $serviceReport;
    public User $user;
    public bool $notifySelf;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ServiceReport $serviceReport, User $user, bool $notifySelf)
    {
        $this->serviceReport = $serviceReport;
        $this->user = $user;
        $this->notifySelf = $notifySelf;
    }
}
