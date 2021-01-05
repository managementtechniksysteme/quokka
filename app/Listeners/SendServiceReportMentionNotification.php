<?php

namespace App\Listeners;

use App\Events\ServiceReportCreatedEvent;
use App\Events\ServiceReportUpdatedEvent;
use App\Helpers\Mentions;
use App\Notifications\ServiceReportMentionNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendServiceReportMentionNotification implements ShouldQueue
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

    public function handleServiceReportCreated(ServiceReportCreatedEvent $event)
    {
        foreach (Mentions::extractMentionedUsers($event->serviceReport->comment) as $mentionedUser) {
            $mentionedUser->notify(new ServiceReportMentionNotification($event->serviceReport));
        }
    }

    public function handleServiceReportUpdated(ServiceReportUpdatedEvent $event)
    {
        foreach (Mentions::extractMentionedUsers($event->serviceReport->comment) as $mentionedUser) {
            $mentionedUser->notify(new ServiceReportMentionNotification($event->serviceReport));
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            ServiceReportCreatedEvent::class,
            [SendServiceReportMentionNotification::class, 'handleServiceReportCreated']
        );

        $events->listen(
            ServiceReportUpdatedEvent::class,
            [SendServiceReportMentionNotification::class, 'handleServiceReportUpdated']
        );
    }
}
