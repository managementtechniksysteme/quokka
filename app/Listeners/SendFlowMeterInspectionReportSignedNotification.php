<?php

namespace App\Listeners;

use App\Events\FlowMeterInspectionReportSignedEvent;
use App\Models\ApplicationSettings;
use App\Notifications\FlowMeterInspectionReportSignedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendFlowMeterInspectionReportSignedNotification implements ShouldQueue
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
     * @param  FlowMeterInspectionReportSignedEvent  $event
     * @return void
     */
    public function handle(FlowMeterInspectionReportSignedEvent $event)
    {
        $flowMeterInspectionReport = $event->flowMeterInspectionReport;

        $flowMeterInspectionReport->employee->user->notify(new FlowMeterInspectionReportSignedNotification($flowMeterInspectionReport));

        $user = optional(ApplicationSettings::get()->signatureNotifyUser)->employee->person ?? null;

        if ($user) {
            $user->notify(new FlowMeterInspectionReportSignedNotification($flowMeterInspectionReport));
        }
    }
}
