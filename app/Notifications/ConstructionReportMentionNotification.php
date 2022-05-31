<?php

namespace App\Notifications;

use App\Models\ConstructionReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ConstructionReportMentionNotification extends Notification implements ShouldQueue
{
    use Queueable;

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
            ->subject('Du wurdst in einem Bautagesbericht erwähnt (Projekt '.$this->constructionReport->project->name.' #'.$this->constructionReport->number.')')
            ->markdown('emails.construction_report.notification_mention', ['constructionReport' => $this->constructionReport]);
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Du wurdst in einem Bautagesbericht erwähnt')
            ->icon('/icons/icon_512.png')
            ->badge('/icons/icon_alpha_512.png')
            ->body('Du wurdst im Bautagesbericht Projekt '.$this->constructionReport->project->name.' #'.$this->constructionReport->number.' erwähnt')
            ->tag(ConstructionReport::class.':'.$this->constructionReport->id)
            ->data(['url' => route('construction-reports.show', $this->constructionReport)])
            ->vibrate($this->vibrationDuration);
    }
}
