<?php

namespace App\Listeners;

use App\Events\MemoCreatedEvent;
use App\Events\MemoUpdatedEvent;
use App\Helpers\Mentions;
use App\Notifications\MemoMentionNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMemoMentionNotification implements ShouldQueue
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

    public function handleMemoCreated(MemoCreatedEvent $event)
    {
        $memo = $event->memo;

        if ($memo->comment) {
            foreach (Mentions::extractMentionedUsers($memo->comment) as $mentionedUser) {
                $mentionedUser->notify(new MemoMentionNotification($memo, $event->user, $event->notifySelf));
            }
        }
    }

    public function handleMemoUpdated(MemoUpdatedEvent $event)
    {
        $memo = $event->memo;

        if ($memo->comment) {
            foreach (Mentions::extractMentionedUsers($memo->comment) as $mentionedUser) {
                $mentionedUser->notify(new MemoMentionNotification($memo, $event->user, $event->notifySelf));
            }
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            MemoCreatedEvent::class,
            [SendMemoMentionNotification::class, 'handleMemoCreated']
        );

        $events->listen(
            MemoUpdatedEvent::class,
            [SendMemoMentionNotification::class, 'handleMemoUpdated']
        );
    }
}
