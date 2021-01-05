<?php

namespace App\Listeners;

use App\Events\TaskCreatedEvent;
use App\Events\TaskUpdatedEvent;
use App\Models\Employee;
use App\Models\Task;
use App\Notifications\TaskInvolvedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTaskInvolvedNotification implements ShouldQueue
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

        $involvedUsers = $this->getInvolvedUsers($task);

        foreach ($involvedUsers as $involvedUser) {
            $involvedUser->notify(new TaskInvolvedNotification($task, true));
        }
    }

    public function handleTaskUpdated(TaskUpdatedEvent $event)
    {
        $task = $event->task;

        $involvedUsers = $this->getInvolvedUsers($task);

        foreach ($involvedUsers as $involvedUser) {
            $involvedUser->notify(new TaskInvolvedNotification($task, false));
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            TaskCreatedEvent::class,
            [SendTaskInvolvedNotification::class, 'handleTaskCreated']
        );

        $events->listen(
            TaskUpdatedEvent::class,
            [SendTaskInvolvedNotification::class, 'handleTaskUpdated']
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
