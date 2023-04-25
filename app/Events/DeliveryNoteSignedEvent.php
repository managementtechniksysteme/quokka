<?php

namespace App\Events;

use App\Models\DeliveryNote;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeliveryNoteSignedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public DeliveryNote $deliveryNote;

    /**
    * Create a new event instance.
    *
    * @return void
    */
    public function __construct(DeliveryNote $deliveryNote)
    {
        $this->deliveryNote = $deliveryNote;
    }
}
