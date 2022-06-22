<?php

namespace App\Events;

use App\Models\TaskComment;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public TaskComment $comment;
    public User $user;
    public bool $notifySelf;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TaskComment $comment, User $user, bool $notifySelf)
    {
        $this->comment = $comment;
        $this->user = $user;
        $this->notifySelf = $notifySelf;
    }
}
