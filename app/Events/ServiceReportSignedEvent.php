<?php

namespace App\Events;

use App\Models\ServiceReport;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServiceReportSignedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $serviceReport;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ServiceReport $serviceReport)
    {
        $this->serviceReport = $serviceReport;
    }
}
