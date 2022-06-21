<?php

namespace App\Notifications;

use App\Models\InspectionReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class InspectionReportSignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public InspectionReport $inspectionReport;
    private array $vibrationDuration = ['100'];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(InspectionReport $inspectionReport)
    {
        $this->inspectionReport = $inspectionReport;
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
            ->subject('Ein Prüfbericht wurde unterschrieben (Anlage: '.$this->inspectionReport->equipment_identifier.', Kunde: '.$this->inspectionReport->project->company->name.')')
            ->markdown('emails.inspection_report.notification_signed', ['inspectionReport' => $this->inspectionReport]);
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Ein Prüfbericht wurde unterschrieben')
            ->icon('/icons/icon_512.png')
            ->badge('/icons/icon_alpha_512.png')
            ->body('Der Prüfbericht der Anlage '.$this->inspectionReport->equipment_identifier.' (Kunde: '.$this->inspectionReport->project->company->name.') vom '.$this->inspectionReport->inspected_on.' wurde unterschrieben')
            ->tag(InspectionReport::class.':'.$this->inspectionReport->id)
            ->data(['url' => route('inspection-reports.show', $this->inspectionReport)])
            ->vibrate($this->vibrationDuration);
    }
}
