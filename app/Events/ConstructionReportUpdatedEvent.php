<?php

namespace App\Events;

use App\Models\ConstructionReport;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConstructionReportUpdatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ConstructionReport $constructionReport;
    public User $user;
    public bool $notifySelf;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ConstructionReport $constructionReport, User $user, bool $notifySelf)
    {
        $this->constructionReport = $constructionReport;
        $this->user = $user;
        $this->notifySelf = $notifySelf;
    }
}
