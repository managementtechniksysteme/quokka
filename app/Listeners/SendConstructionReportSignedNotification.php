<?php

namespace App\Listeners;

use App\Events\ConstructionReportSignedEvent;
use App\Models\ApplicationSettings;
use App\Notifications\ConstructionReportSignedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendConstructionReportSignedNotification implements ShouldQueue
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
     * @param  ConstructionReportSignedEvent  $event
     * @return void
     */
    public function handle(ConstructionReportSignedEvent $event)
    {
        $constructionReport = $event->constructionReport;

        $constructionReport->employee->user->notify(new ConstructionReportSignedNotification($constructionReport));

        $user = optional(ApplicationSettings::get()->signatureNotifyUser)->employee->person ?? null;

        if ($user) {
            $user->notify(new ConstructionReportSignedNotification($constructionReport));
        }
    }
}
