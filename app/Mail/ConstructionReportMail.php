<?php

namespace App\Mail;

use App\Models\ConstructionReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

class ConstructionReportMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public ConstructionReport $constructionReport;
    public ?MediaCollection $mail_attachments;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ConstructionReport $constructionReport, ?MediaCollection $attachments)
    {
        $this->constructionReport = $constructionReport;
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
            ->subject('Bautagesbericht '.$this->constructionReport->project->name.' #'.$this->constructionReport->number)
            ->markdown('emails.construction_report.construction_report');

        if ($this->mail_attachments) {
            foreach ($this->mail_attachments as $attachment) {
                $mail->attach($attachment->getPath());
            }
        }

        return $mail;
    }
}
