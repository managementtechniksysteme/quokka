<?php

namespace App\Mail;

use App\Models\Memo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MemoMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $memo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Memo $memo)
    {
        $this->memo = $memo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Aktenvermerk ' . $this->memo->title . '(' . $this->memo->project->name . ' #'. $this->memo->number . ')')
            ->markdown('emails.memo.memo');
    }
}
