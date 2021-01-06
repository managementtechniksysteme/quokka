<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;
use PragmaRX\Version\Package\Version;

class ApplicationVersionUpdateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private array $vibrationDuration = ['100'];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
                    ->subject('Eine neue Quokka Version ist verfügbar')
                    ->markdown('emails.notification_application_version');
    }

    public function toWebPush($notifiable, $notification)
    {
        $version = new Version();

        return (new WebPushMessage)
            ->title('Eine neue Quokka Version ist verfügbar')
            ->icon('/icons/icon_512.png')
            ->badge('/icons/icon_alpha_512.png')
            ->body('Quokka Version '.$version->compact().' ist nun zur Verwendung verfügbar.')
            ->tag(ApplicationVersionUpdateNotification::class)
            ->data(['url' => route('changelog.show')])
            ->vibrate($this->vibrationDuration);
    }
}
