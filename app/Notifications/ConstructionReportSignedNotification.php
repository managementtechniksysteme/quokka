<?php

namespace App\Notifications;

use App\Models\ConstructionReport;
use App\Traits\TargetsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ConstructionReportSignedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use TargetsNotification;

    public ConstructionReport $constructionReport;
    private array $vibrationDuration = ['100'];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ConstructionReport $constructionReport)
    {
        $this->constructionReport = $constructionReport;
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
            'model' => ConstructionReport::class,
            'type' => 'ConstructionReport',
            'id' => $this->constructionReport->id,
            'route' => route('construction-reports.show', $this->constructionReport->id),
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
            ->subject('Ein Bautagesbericht wurde unterschrieben (Projekt '.$this->constructionReport->project->name.' #'.$this->constructionReport->number.')')
            ->markdown('emails.construction_report.notification_signed', ['constructionReport' => $this->constructionReport]);
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Ein Bautagesbericht wurde unterschrieben')
            ->icon('/icons/icon_512.png')
            ->badge('/icons/icon_alpha_512.png')
            ->body('Der Bautagesbericht Projekt '.$this->constructionReport->project->name.' #'.$this->constructionReport->number.' wurde unterschrieben.')
            ->tag(ConstructionReport::class.':'.$this->constructionReport->id)
            ->data(['url' => route('Construction-reports.show', $this->constructionReport)])
            ->vibrate($this->vibrationDuration);
    }
}
