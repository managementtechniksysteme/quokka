<?php

namespace App\Listeners;

use App\Events\FlowMeterInspectionReportCreatedEvent;
use App\Events\FlowMeterInspectionReportUpdatedEvent;
use App\Helpers\Mentions;
use App\Notifications\FlowMeterInspectionReportMentionNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendFlowMeterInspectionReportMentionNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handleFlowMeterInspectionReportCreated(FlowMeterInspectionReportCreatedEvent $event)
    {
        foreach (Mentions::extractMentionedUsers($event->flowMeterInspectionReport->comment) as $mentionedUser) {
            $mentionedUser->notify(new FlowMeterInspectionReportMentionNotification($event->flowMeterInspectionReport, $event->user, $event->notifySelf));
        }
    }

    public function handleFlowMeterInspectionReportUpdated(FlowMeterInspectionReportUpdatedEvent $event)
    {
        foreach (Mentions::extractMentionedUsers($event->flowMeterInspectionReport->comment) as $mentionedUser) {
            $mentionedUser->notify(new FlowMeterInspectionReportMentionNotification($event->flowMeterInspectionReport, $event->user, $event->notifySelf));
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            FlowMeterInspectionReportCreatedEvent::class,
            [SendFlowMeterInspectionReportMentionNotification::class, 'handleFlowMeterInspectionReportCreated']
        );

        $events->listen(
            FlowMeterInspectionReportUpdatedEvent::class,
            [SendFlowMeterInspectionReportMentionNotification::class, 'handleFlowMeterInspectionReportUpdated']
        );
    }
}
