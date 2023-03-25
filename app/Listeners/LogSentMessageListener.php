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
        $toAddresses = [];
        $ccAddresses = [];
        $bccAddresses = [];

        foreach ($event->message->getTo() as $toAddress) {
            $toAddresses[$toAddress->getAddress()] = $toAddress->getName() ?: null;
        }

        foreach ($event->message->getCc() as $ccAddress) {
            $ccAddresses[$ccAddress->getAddress()] = $ccAddress->getName() ?: null;
        }

        foreach ($event->message->getBcc() as $bccAddress) {
            $bccAddresses[$bccAddress->getAddress()] = $bccAddress->getName() ?: null;
        }

        \Log::info("Email properties");
        \Log::info(print_r($toAddresses, true));
        \Log::info(print_r($ccAddresses, true));
        \Log::info(print_r($bccAddresses, true));


        activity()
            ->withProperties([
                'subject' => $event->message->getSubject(),
                'to' => $toAddresses,
                'cc' => $ccAddresses,
                'bcc' => $bccAddresses,
            ])
            ->event('emailSent')
            ->log('emailSent');
    }
}
