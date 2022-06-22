<?php

namespace App\Listeners;

use App\Events\InspectionReportCreatedEvent;
use App\Events\InspectionReportUpdatedEvent;
use App\Helpers\Mentions;
use App\Notifications\InspectionReportMentionNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendInspectionReportMentionNotification implements ShouldQueue
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

    public function handleInspectionReportCreated(InspectionReportCreatedEvent $event)
    {
        foreach (Mentions::extractMentionedUsers($event->inspectionReport->comment) as $mentionedUser) {
            $mentionedUser->notify(new InspectionReportMentionNotification($event->inspectionReport, $event->user, $event->notifySelf));
        }
    }

    public function handleInspectionReportUpdated(InspectionReportUpdatedEvent $event)
    {
        foreach (Mentions::extractMentionedUsers($event->inspectionReport->comment) as $mentionedUser) {
            $mentionedUser->notify(new InspectionReportMentionNotification($event->inspectionReport, $event->user, $event->notifySelf));
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            InspectionReportCreatedEvent::class,
            [SendInspectionReportMentionNotification::class, 'handleInspectionReportCreated']
        );

        $events->listen(
            InspectionReportUpdatedEvent::class,
            [SendInspectionReportMentionNotification::class, 'handleInspectionReportUpdated']
        );
    }
}
