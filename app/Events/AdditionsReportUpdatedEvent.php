<?php

namespace App\Events;

use App\Models\AdditionsReport;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdditionsReportUpdatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public AdditionsReport $additionsReport;
    public User $user;
    public bool $notifySelf;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AdditionsReport $additionsReport, User $user, bool $notifySelf)
    {
        $this->additionsReport = $additionsReport;
        $this->user = $user;
        $this->notifySelf = $notifySelf;
    }
}
