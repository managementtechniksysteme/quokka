<?php

namespace App\Events;

use App\Models\Task;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Task $task;
    public User $user;
    public bool $notifySelf;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Task $task, User $user, bool $notifySelf)
    {
        $this->task = $task;
        $this->user = $user;
        $this->notifySelf = $notifySelf;
    }
}
