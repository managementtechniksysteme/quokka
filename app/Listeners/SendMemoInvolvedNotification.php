<?php

namespace App\Listeners;

use App\Events\MemoCreatedEvent;
use App\Events\MemoUpdatedEvent;
use App\Models\Memo;
use App\Models\Person;
use App\Notifications\MemoInvolvedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMemoInvolvedNotification implements ShouldQueue
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

    public function handleMemoCreated(MemoCreatedEvent $event)
    {
        $memo = $event->memo;

        $involvedUsers = $this->getInvolvedUsers($memo);

        foreach ($involvedUsers as $involvedUser) {
            $involvedUser->notify(new MemoInvolvedNotification($memo, true));
        }
    }

    public function handleMemoUpdated(MemoUpdatedEvent $event)
    {
        $memo = $event->memo;

        $involvedUsers = $this->getInvolvedUsers($memo);

        foreach ($involvedUsers as $involvedUser) {
            $involvedUser->notify(new MemoInvolvedNotification($memo, false));
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            MemoCreatedEvent::class,
            [SendMemoInvolvedNotification::class, 'handleMemoCreated']
        );

        $events->listen(
            MemoUpdatedEvent::class,
            [SendMemoInvolvedNotification::class, 'handleMemoUpdated']
        );
    }

    private function getInvolvedUsers(Memo $memo)
    {
        $composer = $memo->employeeComposer->person;
        $recipient = $memo->personRecipient;
        $presentPeople = $memo->presentPeople;
        $notifiedPeople = $memo->notifiedPeople;

        $all = collect([$composer])->merge($recipient)->merge($presentPeople)->merge($notifiedPeople);

        $people = Person::whereIn('id', $all->pluck('id'))
            ->whereHas('employee', function ($query) {
                return $query->has('user');
            })
            ->with('employee.user')->get();

        if ($people->isEmpty()) {
            return [];
        } else {
            return $people->pluck('employee.user');
        }
    }
}
