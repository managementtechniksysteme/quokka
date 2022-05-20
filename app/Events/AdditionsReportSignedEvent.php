<?php

namespace App\Events;

use App\Models\AdditionsReport;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdditionsReportSignedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public AdditionsReport $additionsReport;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AdditionsReport $additionsReport)
    {
        $this->additionsReport = $additionsReport;
    }
}
