<?php

namespace App\Mail;

use App\Models\FlowMeterInspectionReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

class FlowMeterInspectionReportMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public FlowMeterInspectionReport $flowMeterInspectionReport;
    public ?MediaCollection $mail_attachments;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(FlowMeterInspectionReport $flowMeterInspectionReport, ?MediaCollection $attachments)
    {
        $this->flowMeterInspectionReport = $flowMeterInspectionReport;
        $this->mail_attachments = $attachments;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this
            ->subject('Prüfbericht für Durchflussmesseinrichtungen der Anlage '.$this->flowMeterInspectionReport->equipment_identifier.', (Kunde: '.$this->flowMeterInspectionReport->project->company->name.') vom '.$this->flowMeterInspectionReport->inspected_on)
            ->markdown('emails.flow_meter_inspection_report.flow_meter_inspection_report');

        if ($this->mail_attachments) {
            foreach ($this->mail_attachments as $attachment) {
                $mail->attach($attachment->getPath());
            }
        }

        return $mail;
    }
}
