<?php

namespace App\Notifications;

use App\Models\DeliveryNote;
use App\Traits\TargetsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class DeliveryNoteSignedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use TargetsNotification;

    public DeliveryNote $deliveryNote;
    private array $vibrationDuration = ['100'];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(DeliveryNote $deliveryNote)
    {
        $this->deliveryNote = $deliveryNote;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            DatabaseChannel::class,
            MailChannel::class,
            WebPushChannel::class,
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'model' => DeliveryNote::class,
            'type' => 'DeliveryNote',
            'id' => $this->deliveryNote->id,
            'route' => route('delivery-notes.show', $this->deliveryNote->id),
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Ein Lieferschein wurde unterschrieben ('.$this->deliveryNote->title.', Projekt '.$this->deliveryNote->project->name.')')
            ->markdown('emails.delivery_note.notification_signed', ['deliveryNote' => $this->deliveryNote]);
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Ein Lieferschein wurde unterschrieben')
            ->icon('/icons/icon_512.png')
            ->badge('/icons/icon_alpha_512.png')
            ->body('Der Lieferschein ' . $this->deliveryNote->title . ' (Projekt '.$this->deliveryNote->project->name.') wurde unterschrieben.')
            ->tag(DeliveryNote::class.':'.$this->deliveryNote->id)
            ->data(['url' => route('delivery-notes.show', $this->deliveryNote)])
            ->vibrate($this->vibrationDuration);
    }
}
