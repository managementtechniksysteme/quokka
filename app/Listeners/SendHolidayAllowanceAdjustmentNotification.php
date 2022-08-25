<?php

namespace App\Listeners;

use App\Events\HolidayAllowanceAdjustedEvent;
use App\Notifications\HolidayAllowanceAdjustmentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendHolidayAllowanceAdjustmentNotification implements ShouldQueue
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

    public function handleHolidayAllowanceAdjusted(HolidayAllowanceAdjustedEvent $event)
    {
        $employee = $event->employee;
        $oldHolidayAllowance = $event->oldHolidayAllowance;
        $currentHolidayAllowance = $event->currentHolidayAllowance;
        $user = $event->user;
        $notifySelf = $event->notifySelf;
        $manualAdjustment = $event->manualAdjustment;


        $employee->user->notify(
            new HolidayAllowanceAdjustmentNotification(
                $oldHolidayAllowance, $currentHolidayAllowance, $user, $notifySelf, $manualAdjustment
            )
        );
    }

    public function subscribe($events)
    {
        $events->listen(
            HolidayAllowanceAdjustedEvent::class,
            [SendHolidayAllowanceAdjustmentNotification::class, 'handleHolidayAllowanceAdjusted']
        );
    }
}
