<?php

namespace App\Listeners;

use App\Events\TaskCreatedEvent;
use App\Events\TaskUpdatedEvent;
use App\Helpers\Mentions;
use App\Notifications\TaskMentionNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTaskMentionNotification implements ShouldQueue
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

    public function handleTaskCreated(TaskCreatedEvent $event)
    {
        $task = $event->task;

        if ($task->comment) {
            foreach (Mentions::extractMentionedUsers($task->comment) as $mentionedUser) {
                $mentionedUser->notify(new TaskMentionNotification($task));
            }
        }
    }

    public function handleTaskUpdated(TaskUpdatedEvent $event)
    {
        $task = $event->task;

        if ($task->comment) {
            foreach (Mentions::extractMentionedUsers($task->comment) as $mentionedUser) {
                $mentionedUser->notify(new TaskMentionNotification($task));
            }
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            TaskCreatedEvent::class,
            [SendTaskMentionNotification::class, 'handleTaskCreated']
        );

        $events->listen(
            TaskUpdatedEvent::class,
            [SendTaskMentionNotification::class, 'handleTaskUpdated']
        );
    }
}
