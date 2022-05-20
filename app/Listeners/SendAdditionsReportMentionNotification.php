<?php

namespace App\Listeners;

use App\Events\AdditionsReportCreatedEvent;
use App\Events\AdditionsReportUpdatedEvent;
use App\Helpers\Mentions;
use App\Notifications\AdditionsReportMentionNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAdditionsReportMentionNotification implements ShouldQueue
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
        foreach (Mentions::extractMentionedUsers($event->additionsReport->comment) as $mentionedUser) {
            $mentionedUser->notify(new AdditionsReportMentionNotification($event->additionsReport));
        }
    }

    public function handleAdditionseReportUpdated(AdditionsReportUpdatedEvent $event)
    {
        foreach (Mentions::extractMentionedUsers($event->additionsReport->comment) as $mentionedUser) {
            $mentionedUser->notify(new AdditionsReportMentionNotification($event->additionsReport));
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            AdditionsReportCreatedEvent::class,
            [SendAdditionsReportMentionNotification::class, 'handleAdditionsReportCreated']
        );

        $events->listen(
            AdditionsReportUpdatedEvent::class,
            [SendAdditionsReportMentionNotification::class, 'handleadditionsReportUpdated']
        );
    }
}
