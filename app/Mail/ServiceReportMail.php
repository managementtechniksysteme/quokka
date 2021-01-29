<?php

namespace App\Mail;

use App\Models\ServiceReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

class ServiceReportMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public ServiceReport $serviceReport;
    public ?MediaCollection $mail_attachments;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ServiceReport $serviceReport, ?MediaCollection $attachments)
    {
        $this->serviceReport = $serviceReport;
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
            ->subject('Servicebericht '.$this->serviceReport->project->name.' #'.$this->serviceReport->number)
            ->markdown('emails.service_report.service_report');

        if ($this->mail_attachments) {
            foreach ($this->mail_attachments as $attachment) {
                $mail->attach($attachment->getPath());
            }
        }

        return $mail;
    }
}
