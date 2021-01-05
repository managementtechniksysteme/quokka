<?php

namespace App\Listeners;

use App\Events\CommentCreatedEvent;
use App\Events\CommentUpdatedEvent;
use App\Models\User;
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
        foreach ($this->extractMentionedUsers($event->comment->comment) as $mentionedUser) {
            $mentionedUser->notify(new CommentMentionNotification($event->comment));
        }
    }

    public function handleCommentUpdated(CommentUpdatedEvent $event)
    {
        foreach ($this->extractMentionedUsers($event->comment->comment) as $mentionedUser) {
            $mentionedUser->notify(new CommentMentionNotification($event->comment));
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

    private function extractMentionedUsers(string $text)
    {
        preg_match_all('/(?:^|[^a-zA-Z0-9_＠!@#$%&*])(?:(?:@|＠)(?!\/))([a-zA-Z0-9\/_]{1,15})(?:\b(?!@|＠)|$)/', $text, $matches);

        $usernames = $matches[1];

        $mentionedUsers = User::whereIn('username', $usernames)->get();

        return $mentionedUsers;
    }
}
