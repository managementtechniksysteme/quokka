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

class AdditionsReportInvolvedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use TargetsNotification;

    public AdditionsReport $additionsReport;
    public bool $isNew;
    private array $vibrationDuration = ['100'];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(AdditionsReport $additionsReport, bool $isNew, User $user, bool $notifySelf)
    {
        $this->additionsReport = $additionsReport;
        $this->isNew = $isNew;
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
            'created' => $this->isNew,
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
        if ($this->isNew) {
            $subject = 'Es wurde ein Regiebericht erstellt, an dem du beteiligt bist (Projekt '.$this->additionsReport->project->name.' #'.$this->additionsReport->number.')';
        } else {
            $subject = 'Es wurde ein Regiebericht bearbeitet, an dem du beteiligt bist (Projekt '.$this->additionsReport->project->name.' #'.$this->additionsReport->number.')';
        }

        return (new MailMessage)
                    ->subject($subject)
                    ->markdown('emails.additions_report.notification_involved', ['additionsReport' => $this->additionsReport, 'isNew' => $this->isNew]);
    }

    public function toWebPush($notifiable, $notification)
    {
        if ($this->isNew) {
            $title = 'Ein Regiebericht wurde erstellt';
            $body = 'Der Regiebericht Projekt '.$this->additionsReport->project->name.' #'.$this->additionsReport->number.', an dem du beteiligt bist, wurde erstellt.';
        } else {
            $title = 'Eine Regiebericht wurde bearbeitet';
            $body = 'Der Regiebericht Projekt '.$this->additionsReport->project->name.' #'.$this->additionsReport->number.', an dem du beteiligt bist, wurde bearbeitet.';
        }

        return (new WebPushMessage)
            ->title($title)
            ->icon('/icons/icon_512.png')
            ->badge('/icons/icon_alpha_512.png')
            ->body($body)
            ->tag(AdditionsReport::class.':'.$this->additionsReport->id)
            ->data(['url' => route('additions-reports.show', $this->additionsReport)])
            ->vibrate($this->vibrationDuration);
    }
}
