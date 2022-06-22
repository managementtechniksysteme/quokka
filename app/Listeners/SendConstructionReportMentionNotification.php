<?php

namespace App\Listeners;

use App\Events\ConstructionReportCreatedEvent;
use App\Events\ConstructionReportUpdatedEvent;
use App\Helpers\Mentions;
use App\Notifications\ConstructionReportMentionNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendConstructionReportMentionNotification implements ShouldQueue
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
        foreach (Mentions::extractMentionedUsers($event->constructionReport->comment) as $mentionedUser) {
            $mentionedUser->notify(new ConstructionReportMentionNotification($event->constructionReport, $event->user, $event->notifySelf));
        }
    }

    public function handleConstructionReportUpdated(ConstructionReportUpdatedEvent $event)
    {
        foreach (Mentions::extractMentionedUsers($event->constructionReport->comment) as $mentionedUser) {
            $mentionedUser->notify(new ConstructionReportMentionNotification($event->constructionReport, $event->user, $event->notifySelf));
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            ConstructionReportCreatedEvent::class,
            [SendConstructionReportMentionNotification::class, 'handleConstructionReportCreated']
        );

        $events->listen(
            ConstructionReportUpdatedEvent::class,
            [SendConstructionReportMentionNotification::class, 'handleConstructionReportUpdated']
        );
    }
}
