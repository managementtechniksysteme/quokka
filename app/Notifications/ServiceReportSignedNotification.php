<?php

namespace App\Notifications;

use App\Models\ServiceReport;
use App\Traits\TargetsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ServiceReportSignedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use TargetsNotification;

    public ServiceReport $serviceReport;
    private array $vibrationDuration = ['100'];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ServiceReport $serviceReport)
    {
        $this->serviceReport = $serviceReport;
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
            'model' => ServiceReport::class,
            'type' => 'ServiceReport',
            'id' => $this->serviceReport->id,
            'route' => route('service-reports.show', $this->serviceReport->id),
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
            ->subject('Ein Servicebericht wurde unterschrieben (Projekt '.$this->serviceReport->project->name.' #'.$this->serviceReport->number.')')
            ->markdown('emails.service_report.notification_signed', ['serviceReport' => $this->serviceReport]);
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Ein Servicebericht wurde unterschrieben')
            ->icon('/icons/icon_512.png')
            ->badge('/icons/icon_alpha_512.png')
            ->body('Der Servicebericht Projekt '.$this->serviceReport->project->name.' #'.$this->serviceReport->number.' wurde unterschrieben.')
            ->tag(ServiceReport::class.':'.$this->serviceReport->id)
            ->data(['url' => route('service-reports.show', $this->serviceReport)])
            ->vibrate($this->vibrationDuration);
    }
}
