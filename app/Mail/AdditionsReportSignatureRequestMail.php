<?php

namespace App\Mail;

use App\Models\AdditionsReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdditionsReportSignatureRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public AdditionsReport $additionsReport;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(AdditionsReport $additionsReport)
    {
        $this->additionsReport = $additionsReport;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Regiebericht unterschreiben ('.$this->additionsReport->project->name.' #'.$this->additionsReport->number.')')
            ->markdown('emails.additions_report.signature_request');
    }
}
