<?php

namespace App\Mail;

use App\Models\InspectionReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

class InspectionReportMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public InspectionReport $inspectionReport;
    public ?MediaCollection $mail_attachments;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(InspectionReport $inspectionReport, ?MediaCollection $attachments)
    {
        $this->inspectionReport = $inspectionReport;
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
            ->subject('Prüfbericht der Anlage '.$this->inspectionReport->equipment_identifier.', (Kunde: '.$this->inspectionReport->project->company->name.') vom '.$this->inspectionReport->inspected_on)
            ->markdown('emails.inspection_report.inspection_report');

        if ($this->mail_attachments) {
            foreach ($this->mail_attachments as $attachment) {
                $mail->attach($attachment->getPath());
            }
        }

        return $mail;
    }
}
