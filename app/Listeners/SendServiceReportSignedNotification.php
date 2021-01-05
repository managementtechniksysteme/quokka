<?php

namespace App\Listeners;

use App\Events\ServiceReportSignedEvent;
use App\Models\User;
use App\Notifications\ServiceReportSignedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendServiceReportSignedNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ServiceReportSignedEvent  $event
     * @return void
     */
    public function handle(ServiceReportSignedEvent $event)
    {
        $serviceReport = $event->serviceReport;

        $serviceReport->employee->notify(new ServiceReportSignedNotification($serviceReport));

        // TODO: make dynamic
        User::whereUsername('mst')->first()->notify(new ServiceReportSignedNotification($serviceReport));
    }
}
