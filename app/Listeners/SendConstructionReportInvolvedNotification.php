<?php

namespace App\Listeners;

use App\Events\ConstructionReportCreatedEvent;
use App\Events\ConstructionReportUpdatedEvent;
use App\Models\ConstructionReport;
use App\Models\Employee;
use App\Notifications\ConstructionReportInvolvedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendConstructionReportInvolvedNotification implements ShouldQueue
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

    public function handleConstructionReportCreated(ConstructionReportCreatedEvent $event)
    {
        $constructionReport = $event->constructionReport;

        $involvedUsers = $this->getInvolvedUsers($constructionReport);

        foreach ($involvedUsers as $involvedUser) {
            $involvedUser->notify(new ConstructionReportInvolvedNotification($constructionReport, true, $event->user, $event->notifySelf));
        }
    }

    public function handleConstructionReportUpdated(ConstructionReportUpdatedEvent $event)
    {
        $constructionReport = $event->constructionReport;

        $involvedUsers = $this->getInvolvedUsers($constructionReport);

        foreach ($involvedUsers as $involvedUser) {
            $involvedUser->notify(new ConstructionReportInvolvedNotification($constructionReport, false, $event->user, $event->notifySelf));
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            ConstructionReportCreatedEvent::class,
            [SendConstructionReportInvolvedNotification::class, 'handleConstructionReportCreated']
        );

        $events->listen(
            ConstructionReportUpdatedEvent::class,
            [SendConstructionReportInvolvedNotification::class, 'handleConstructionReportUpdated']
        );
    }

    private function getInvolvedUsers(ConstructionReport $constructionReport)
    {
        $involvedEmployees = $constructionReport->involvedEmployees;

        $employees = Employee::whereIn('person_id', $involvedEmployees->pluck('person_id'))
            ->whereHas('user')
            ->with('user')->get();

        if ($employees->isEmpty()) {
            return [];
        } else {
            return $employees->pluck('user');
        }
    }
}
