<?php

namespace App\Mail;

use App\Models\InspectionReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InspectionReportSignatureRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public InspectionReport $inspectionReport;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(InspectionReport $inspectionReport)
    {
        $this->inspectionReport = $inspectionReport;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Prüfbericht unterschreiben (Anlage '.$this->inspectionReport->equipment_identifier.')')
            ->markdown('emails.inspection_report.signature_request');
    }
}
