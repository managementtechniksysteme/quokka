<?php

namespace App\Listeners;

use App\Events\ServiceReportSignedEvent;
use App\Models\ApplicationSettings;
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

        $serviceReport->employee->user->notify(new ServiceReportSignedNotification($serviceReport));

        $user = optional(ApplicationSettings::get()->signatureNotifyUser)->employee->person ?? null;

        if ($user) {
            $user->notify(new ServiceReportSignedNotification($serviceReport));
        }
    }
}
