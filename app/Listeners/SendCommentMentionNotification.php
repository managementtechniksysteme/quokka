<?php

namespace App\Listeners;

use App\Events\CommentCreatedEvent;
use App\Events\CommentUpdatedEvent;
use App\Helpers\Mentions;
use App\Notifications\CommentMentionNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCommentMentionNotification implements ShouldQueue
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
     * @param  CommentCreatedEvent  $event
     * @return void
     */
    public function handleCommentCreated(CommentCreatedEvent $event)
    {
        foreach (Mentions::extractMentionedUsers($event->comment->comment) as $mentionedUser) {
            $mentionedUser->notify(new CommentMentionNotification($event->comment, $event->user, $event->notifySelf));
        }
    }

    public function handleCommentUpdated(CommentUpdatedEvent $event)
    {
        foreach (Mentions::extractMentionedUsers($event->comment->comment) as $mentionedUser) {
            $mentionedUser->notify(new CommentMentionNotification($event->comment, $event->user, $event->notifySelf));
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            CommentCreatedEvent::class,
            [SendCommentMentionNotification::class, 'handleCommentCreated']
        );

        $events->listen(
            CommentUpdatedEvent::class,
            [SendCommentMentionNotification::class, 'handleCommentUpdated']
        );
    }
}
