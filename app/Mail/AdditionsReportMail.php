<?php

namespace App\Mail;

use App\Models\AdditionsReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

class AdditionsReportMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public AdditionsReport $additionsReport;
    public ?MediaCollection $mail_attachments;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(AdditionsReport $additionsReport, ?MediaCollection $attachments)
    {
        $this->additionsReport = $additionsReport;
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
            ->subject('Regiebericht '.$this->additionsReport->project->name.' #'.$this->additionsReport->number)
            ->markdown('emails.additions_report.additions_report');

        if ($this->mail_attachments) {
            foreach ($this->mail_attachments as $attachment) {
                $mail->attach($attachment->getPath());
            }
        }

        return $mail;
    }
}
