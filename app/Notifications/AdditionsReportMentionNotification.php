<?php

namespace App\Notifications;

use App\Models\AdditionsReport;
use App\Models\User;
use App\Traits\TargetsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class AdditionsReportMentionNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use TargetsNotification;

    public AdditionsReport $additionsReport;
    private array $vibrationDuration = ['100'];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(AdditionsReport $additionsReport, User $user, bool $notifySelf)
    {
        $this->additionsReport = $additionsReport;
        $this->user = $user;
        $this->notifySelf = $notifySelf;
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
            'model' => AdditionsReport::class,
            'type' => 'AdditionsReport',
            'id' => $this->additionsReport->id,
            'route' => route('additions-reports.show', $this->additionsReport->id),
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
            ->subject('Du wurdest in einem Regiebericht erwähnt (Projekt '.$this->additionsReport->project->name.' #'.$this->additionsReport->number.')')
            ->markdown('emails.additions_report.notification_mention', ['additionsReport' => $this->additionsReport]);
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Du wurdst in einem Regiebericht erwähnt')
            ->icon('/icons/icon_512.png')
            ->badge('/icons/icon_alpha_512.png')
            ->body('Du wurdest im Regiebericht Projekt '.$this->additionsReport->project->name.' #'.$this->additionsReport->number.' erwähnt')
            ->tag(AdditionsReport::class.':'.$this->additionsReport->id)
            ->data(['url' => route('additions-reports.show', $this->additionsReport)])
            ->vibrate($this->vibrationDuration);
    }
}
