<?php

namespace App\Listeners;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogSentMessageListener  implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
    }

    public function handle($event)
    {
        activity()
            ->withProperties([
                'subject' => $event->message->getSubject(),
                'to' => is_array($event->message->getTo()) ? $event->message->getTo() : [$event->message->getTo()],
                'cc' => is_array($event->message->getCc()) ? $event->message->getCc() : [$event->message->getCc()],
                'bcc' => is_array($event->message->getBcc()) ? $event->message->getBcc() : [$event->message->getBcc()],
            ])
            ->event('email')
            ->log('email');
    }
}
