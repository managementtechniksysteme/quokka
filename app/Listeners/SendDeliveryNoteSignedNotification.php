<?php

namespace App\Listeners;

use App\Events\DeliveryNoteSignedEvent;
use App\Models\ApplicationSettings;
use App\Notifications\DeliveryNoteSignedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendDeliveryNoteSignedNotification implements ShouldQueue
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
     * @param  DeliveryNoteSignedEvent  $event
     * @return void
     */
    public function handle(DeliveryNoteSignedEvent $event)
    {
        $deliveryNote = $event->deliveryNote;

        $deliveryNote->employee->user->notify(new DeliveryNoteSignedNotification($deliveryNote));

        $user = optional(ApplicationSettings::get()->signatureNotifyUser)->employee->person ?? null;

        if ($user) {
            $user->notify(new DeliveryNoteSignedNotification($deliveryNote));
        }
    }
}
