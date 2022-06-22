<?php

namespace App\Events;

use App\Models\Memo;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MemoUpdatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Memo $memo;
    public User $user;
    public bool $notifySelf;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Memo $memo, User $user, bool $notifySelf)
    {
        $this->memo = $memo;
        $this->user = $user;
        $this->notifySelf = $notifySelf;
    }
}
