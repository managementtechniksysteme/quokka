<?php

namespace App\Listeners;

use App\Events\InspectionReportSignedEvent;
use App\Models\ApplicationSettings;
use App\Notifications\InspectionReportSignedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendInspectionReportSignedNotification implements ShouldQueue
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
     * @param  InspectionReportSignedEvent  $event
     * @return void
     */
    public function handle(InspectionReportSignedEvent $event)
    {
        $inspectionReport = $event->inspectionReport;

        $inspectionReport->employee->user->notify(new InspectionReportSignedNotification($inspectionReport));

        $user = optional(ApplicationSettings::get()->signatureNotifyUser)->employee->person ?? null;

        if ($user) {
            $user->notify(new InspectionReportSignedNotification($inspectionReport));
        }
    }
}
