<?php

namespace App\Notifications;

use App\Traits\TargetsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;
class ApplicationVersionUpdateNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use TargetsNotification;

    private string $version;
    private array $vibrationDuration = ['100'];

    public function __construct()
    {
        $this->version = sprintf('v%s.%s.%s-%s', config('version.major'), config('version.minor'), config('version.patch'), config('version.commit'));
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
            'version' => $this->version,
            'route' => route('changelog.show'),
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

        return (new WebPushMessage)
            ->title('Eine neue Quokka Version ist verfügbar')
            ->icon('/icons/icon_512.png')
            ->badge('/icons/icon_alpha_512.png')
            ->body('Quokka Version '.$this->version.' ist nun zur Verwendung verfügbar.')
            ->tag(ApplicationVersionUpdateNotification::class)
            ->data(['url' => route('changelog.show')])
            ->vibrate($this->vibrationDuration);
    }
}
