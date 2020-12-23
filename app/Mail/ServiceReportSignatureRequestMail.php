<?php

namespace App\Mail;

use App\Models\ServiceReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ServiceReportSignatureRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $serviceReport;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ServiceReport $serviceReport)
    {
        $this->serviceReport = $serviceReport;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Servicebericht unterschreiben ('.$this->serviceReport->project->name.' #'.$this->serviceReport->number.')')
            ->markdown('emails.service_report.signature_request');
    }
}
