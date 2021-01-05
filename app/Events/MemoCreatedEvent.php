<?php

namespace App\Events;

use App\Models\Memo;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MemoCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $memo;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Memo $memo)
    {
        $this->memo = $memo;
    }
}
