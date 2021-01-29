<?php

namespace App\Mail;

use App\Models\Memo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

class MemoMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Memo $memo;
    public ?MediaCollection $mail_attachments;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Memo $memo, ?MediaCollection $attachments)
    {
        $this->memo = $memo;
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
            ->subject('Aktenvermerk '.$this->memo->title.' ('.$this->memo->project->name.' #'.$this->memo->number.')')
            ->markdown('emails.memo.memo');

        if ($this->mail_attachments) {
            foreach ($this->mail_attachments as $attachment) {
                $mail->attach($attachment->getPath());
            }
        }

        return $mail;
    }
}
