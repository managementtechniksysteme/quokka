<?php

namespace App\Events;

use App\Models\ConstructionReport;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConstructionReportSignedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ConstructionReport $constructionReport;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ConstructionReport $constructionReport)
    {
        $this->constructionReport = $constructionReport;
    }
}
