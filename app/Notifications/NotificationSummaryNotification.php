<?php

namespace App\Notifications;

use App\Traits\TargetsNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;
use PragmaRX\Version\Package\Version;

class NotificationSummaryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private Carbon $date;
    private Collection $notifications;
    private array $vibrationDuration = ['100'];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Carbon $date, Collection $notifications)
    {
        $this->date = $date;
        $this->notifications = $notifications;
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
            MailChannel::class,
            WebPushChannel::class,
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
                    ->subject("Zusammenfassung vom $this->date")
                    ->markdown('emails.notification_summary', ['date' => $this->date, 'notifications' => $this->notifications]);
    }

    public function toWebPush($notifiable, $notification)
    {

        return (new WebPushMessage)
            ->title("Zusammenfassung vom $this->date")
            ->icon('/icons/icon_512.png')
            ->badge('/icons/icon_alpha_512.png')
            ->body("Deine Zusammenfassung vom $this->date wurde als Email verschickt.")
            ->tag(NotificationSummaryNotification::class)
            ->data(['url' => route('notifications.index')])
            ->vibrate($this->vibrationDuration);
    }
}
