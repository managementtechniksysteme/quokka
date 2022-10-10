<?php

namespace App\Notifications;

use App\Models\FlowMeterInspectionReport;
use App\Traits\TargetsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class FlowMeterInspectionReportSignedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use TargetsNotification;

    public FlowMeterInspectionReport $flowMeterInspectionReport;
    private array $vibrationDuration = ['100'];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(FlowMeterInspectionReport $flowMeterInspectionReport)
    {
        $this->flowMeterInspectionReport = $flowMeterInspectionReport;
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
            'model' => FlowMeterInspectionReport::class,
            'type' => 'FlowMeterInspectionReport',
            'id' => $this->flowMeterInspectionReport->id,
            'route' => route('flow-meter-inspection-reports.show', $this->flowMeterInspectionReport->id),
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
            ->subject('Ein Prüfbericht für Durchflussmesseinrichtungen wurde unterschrieben (Anlage: '.$this->flowMeterInspectionReport->equipment_identifier.', Kunde: '.$this->flowMeterInspectionReport->project->company->name.')')
            ->markdown('emails.flow_meter_inspection_report.notification_signed', ['flowMeterInspectionReport' => $this->flowMeterInspectionReport]);
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Ein Prüfbericht für Durchflussmesseinrichtungen wurde unterschrieben')
            ->icon('/icons/icon_512.png')
            ->badge('/icons/icon_alpha_512.png')
            ->body('Der Prüfbericht für Durchflussmesseinrichtungen der Anlage '.$this->flowMeterInspectionReport->equipment_identifier.' (Kunde: '.$this->flowMeterInspectionReport->project->company->name.') vom '.$this->flowMeterInspectionReport->inspected_on.' wurde unterschrieben')
            ->tag(FlowMeterInspectionReport::class.':'.$this->flowMeterInspectionReport->id)
            ->data(['url' => route('flow-meter-inspection-reports.show', $this->flowMeterInspectionReport)])
            ->vibrate($this->vibrationDuration);
    }
}
