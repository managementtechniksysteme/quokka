<?php

namespace App\Mail;

use App\Models\DeliveryNote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeliveryNoteDownloadRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public DeliveryNote $deliveryNote;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(DeliveryNote $deliveryNote)
    {
        $this->deliveryNote = $deliveryNote;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Lieferschein ' . $this->deliveryNote->title . ' herunterladen (Projekt '.$this->deliveryNote->project->name.')')
            ->markdown('emails.delivery_note.download_request');
    }
}
