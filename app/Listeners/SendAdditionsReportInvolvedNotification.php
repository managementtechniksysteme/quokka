<?php

namespace App\Listeners;

use App\Events\AdditionsReportCreatedEvent;
use App\Events\AdditionsReportUpdatedEvent;
use App\Models\AdditionsReport;
use App\Models\Employee;
use App\Notifications\AdditionsReportInvolvedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAdditionsReportInvolvedNotification implements ShouldQueue
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

    public function handleAdditionsReportCreated(AdditionsReportCreatedEvent $event)
    {
        $additionsReport = $event->additionsReport;

        $involvedUsers = $this->getInvolvedUsers($additionsReport);

        foreach ($involvedUsers as $involvedUser) {
            $involvedUser->notify(new AdditionsReportInvolvedNotification($additionsReport, true));
        }
    }

    public function handleAdditionsReportUpdated(AdditionsReportUpdatedEvent $event)
    {
        $additionsReport = $event->additionsReport;

        $involvedUsers = $this->getInvolvedUsers($additionsReport);

        foreach ($involvedUsers as $involvedUser) {
            $involvedUser->notify(new AdditionsReportInvolvedNotification($additionsReport, false));
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            AdditionsReportCreatedEvent::class,
            [SendAdditionsReportInvolvedNotification::class, 'handleAdditionsReportCreated']
        );

        $events->listen(
            AdditionsReportUpdatedEvent::class,
            [SendAdditionsReportInvolvedNotification::class, 'handleAdditionsReportUpdated']
        );
    }

    private function getInvolvedUsers(AdditionsReport $additionsReport)
    {
        $involvedEmployees = $additionsReport->involvedEmployees;

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
