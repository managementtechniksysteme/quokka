<?php

namespace App\Notifications;

use App\Models\AdditionsReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class AdditionsReportSignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public AdditionsReport $additionsReport;
    private array $vibrationDuration = ['100'];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(AdditionsReport $additionsReport)
    {
        $this->additionsReport = $additionsReport;
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
            ->subject('Ein Regiebericht wurde unterschrieben (Projekt '.$this->additionsReport->project->name.' #'.$this->additionsReport->number.')')
            ->markdown('emails.additions_report.notification_signed', ['additionsReport' => $this->additionsReport]);
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Ein Regiebericht wurde unterschrieben')
            ->icon('/icons/icon_512.png')
            ->badge('/icons/icon_alpha_512.png')
            ->body('Der Regiebericht Projekt '.$this->additionsReport->project->name.' #'.$this->additionsReport->number.' wurde unterschrieben.')
            ->tag(AdditionsReport::class.':'.$this->additionsReport->id)
            ->data(['url' => route('additions-reports.show', $this->additionsReport)])
            ->vibrate($this->vibrationDuration);
    }
}
