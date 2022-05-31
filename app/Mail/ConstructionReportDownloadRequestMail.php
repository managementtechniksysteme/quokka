<?php

namespace App\Mail;

use App\Models\ConstructionReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConstructionReportDownloadRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public ConstructionReport $constructionReport;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ConstructionReport $constructionReport)
    {
        $this->constructionReport = $constructionReport;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Bautagesbericht herunterladen ('.$this->constructionReport->project->name.' #'.$this->constructionReport->number.')')
            ->markdown('emails.construction_report.download_request');
    }
}
