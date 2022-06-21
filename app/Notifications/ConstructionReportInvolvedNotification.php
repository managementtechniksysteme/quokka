<?php

namespace App\Notifications;

use App\Models\ConstructionReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ConstructionReportInvolvedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public ConstructionReport $constructionReport;
    public bool $isNew;
    private array $vibrationDuration = ['100'];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ConstructionReport $constructionReport, bool $isNew)
    {
        $this->constructionReport = $constructionReport;
        $this->isNew = $isNew;
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
            'created' => $this->isNew,
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
        if ($this->isNew) {
            $subject = 'Es wurde ein Bautagesbericht erstellt, an dem du beteiligt bist (Projekt '.$this->constructionReport->project->name.' #'.$this->constructionReport->number.')';
        } else {
            $subject = 'Es wurde ein Bautagesbericht bearbeitet, an dem du beteiligt bist (Projekt '.$this->constructionReport->project->name.' #'.$this->constructionReport->number.')';
        }

        return (new MailMessage)
                    ->subject($subject)
                    ->markdown('emails.construction_report.notification_involved', ['constructionReport' => $this->constructionReport, 'isNew' => $this->isNew]);
    }

    public function toWebPush($notifiable, $notification)
    {
        if ($this->isNew) {
            $title = 'Ein Bautagesbericht wurde erstellt';
            $body = 'Der Bautagesbericht Projekt '.$this->constructionReport->project->name.' #'.$this->constructionReport->number.', an dem du beteiligt bist, wurde erstellt.';
        } else {
            $title = 'Eine Bautagesbericht wurde bearbeitet';
            $body = 'Der Bautagesbericht Projekt '.$this->constructionReport->project->name.' #'.$this->constructionReport->number.', an dem du beteiligt bist, wurde bearbeitets.';
        }

        return (new WebPushMessage)
            ->title($title)
            ->icon('/icons/icon_512.png')
            ->badge('/icons/icon_alpha_512.png')
            ->body($body)
            ->tag(ConstructionReport::class.':'.$this->constructionReport->id)
            ->data(['url' => route('construciton-reports.show', $this->constructionReport)])
            ->vibrate($this->vibrationDuration);
    }
}
