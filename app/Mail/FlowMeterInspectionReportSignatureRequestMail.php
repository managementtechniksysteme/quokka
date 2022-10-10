<?php

namespace App\Mail;

use App\Models\FlowMeterInspectionReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FlowMeterInspectionReportSignatureRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public FlowMeterInspectionReport $flowMeterInspectionReport;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(FlowMeterInspectionReport $flowMeterInspectionReport)
    {
        $this->flowMeterInspectionReport = $flowMeterInspectionReport;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Prüfbericht für Durchflussmesseinrichtungen unterschreiben (Anlage '.$this->flowMeterInspectionReport->equipment_identifier.')')
            ->markdown('emails.flow_meter_inspection_report.signature_request');
    }
}
