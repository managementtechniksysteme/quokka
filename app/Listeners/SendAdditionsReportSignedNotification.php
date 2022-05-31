<?php

namespace App\Listeners;

use App\Events\AdditionsReportSignedEvent;
use App\Models\ApplicationSettings;
use App\Notifications\AdditionsReportSignedNotification;
use App\Notifications\ServiceReportSignedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAdditionsReportSignedNotification implements ShouldQueue
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
     * @param  AdditionsReportSignedEvent  $event
     * @return void
     */
    public function handle(AdditionsReportSignedEvent $event)
    {
        $additionsReport = $event->additionsReport;

        $additionsReport->employee->user->notify(new AdditionsReportSignedNotification($additionsReport));

        $user = optional(ApplicationSettings::get()->signatureNotifyUser)->employee->person ?? null;

        if ($user) {
            $user->notify(new AdditionsReportSignedNotification($additionsReport));
        }
    }
}
