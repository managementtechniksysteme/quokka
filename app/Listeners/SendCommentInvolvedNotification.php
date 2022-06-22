<?php

namespace App\Listeners;

use App\Events\CommentCreatedEvent;
use App\Events\CommentUpdatedEvent;
use App\Models\Employee;
use App\Models\Task;
use App\Notifications\CommentInvolvedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCommentInvolvedNotification implements ShouldQueue
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

    public function handleCommentCreated(CommentCreatedEvent $event)
    {
        $comment = $event->comment;

        $involvedUsers = $this->getInvolvedUsers($comment->task);

        foreach ($involvedUsers as $involvedUser) {
            $involvedUser->notify(new CommentInvolvedNotification($comment, true, $event->user, $event->notifySelf));
        }
    }

    public function handleCommentUpdated(CommentUpdatedEvent $event)
    {
        $comment = $event->comment;

        $involvedUsers = $this->getInvolvedUsers($comment->task);

        foreach ($involvedUsers as $involvedUser) {
            $involvedUser->notify(new CommentInvolvedNotification($comment, false, $event->user, $event->notifySelf));
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            CommentCreatedEvent::class,
            [SendCommentInvolvedNotification::class, 'handleCommentCreated']
        );

        $events->listen(
            CommentUpdatedEvent::class,
            [SendCommentInvolvedNotification::class, 'handleCommentUpdated']
        );
    }

    private function getInvolvedUsers(Task $task)
    {
        $responsibleEmployee = $task->responsibleEmployee;
        $involvedEmployees = $task->involvedEmployees;

        $all = collect([$responsibleEmployee])->merge($involvedEmployees);

        $employees = Employee::whereIn('person_id', $all->pluck('person_id'))
            ->whereHas('user')
            ->with('user')->get();

        if ($employees->isEmpty()) {
            return [];
        } else {
            return $employees->pluck('user');
        }
    }
}
