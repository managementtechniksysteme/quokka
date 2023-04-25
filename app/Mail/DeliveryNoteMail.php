<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

class DeliveryNoteMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public DeliveryNote $deliveryNote;
    public ?MediaCollection $mail_attachments;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(DeliveryNote $deliveryNote, ?MediaCollection $attachments)
    {
        $this->deliveryNote = $deliveryNote;
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
            ->subject('Lieferschein ' . $this->deliveryNote->title . '(Projekt' . $this->deliveryNote->project->name . ')')
            ->markdown('emails.delivery_note.delivery_note');

        if ($this->mail_attachments) {
            foreach ($this->mail_attachments as $attachment) {
                $mail->attach($attachment->getPath());
            }
        }

        return $mail;
    }
}
