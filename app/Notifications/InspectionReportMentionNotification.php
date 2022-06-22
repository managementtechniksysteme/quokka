<?php

namespace App\Notifications;

use App\Models\InspectionReport;
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

class InspectionReportMentionNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use TargetsNotification;

    public InspectionReport $inspectionReport;
    private array $vibrationDuration = ['100'];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(InspectionReport $inspectionReport, User $user, bool $notifySelf)
    {
        $this->inspectionReport = $inspectionReport;
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
            'model' => InspectionReport::class,
            'type' => 'InspectionReport',
            'id' => $this->inspectionReport->id,
            'route' => route('inspection-reports.show', $this->inspectionReport->id),
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
            ->subject('Du wurdst in einem Prüfbericht erwähnt (Anlage: '.$this->inspectionReport->equipment_identifier.', Kunde: '.$this->inspectionReport->project->company->name.')')
            ->markdown('emails.inspection_report.notification_mention', ['inspectionReport' => $this->inspectionReport]);
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Du wurdst in einem Prüfbericht erwähnt')
            ->icon('/icons/icon_512.png')
            ->badge('/icons/icon_alpha_512.png')
            ->body('Du wurdst im Prüfbericht der Anlage '.$this->inspectionReport->equipment_identifier.' (Kunde: '.$this->inspectionReport->project->company->name.') vom '.$this->inspectionReport->inspected_on.' erwähnt')
            ->tag(InspectionReport::class.':'.$this->inspectionReport->id)
            ->data(['url' => route('inspection-reports.show', $this->inspectionReport)])
            ->vibrate($this->vibrationDuration);
    }
}
