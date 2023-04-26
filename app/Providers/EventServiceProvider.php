<?php

namespace App\Providers;

use App\Events\AdditionsReportSignedEvent;
use App\Events\ConstructionReportSignedEvent;
use App\Events\DeliveryNoteSignedEvent;
use App\Events\FlowMeterInspectionReportSignedEvent;
use App\Events\InspectionReportSignedEvent;
use App\Events\ServiceReportSignedEvent;
use App\Listeners\LogSentMessageListener;
use App\Listeners\SendAdditionsReportInvolvedNotification;
use App\Listeners\SendAdditionsReportMentionNotification;
use App\Listeners\SendAdditionsReportSignedNotification;
use App\Listeners\SendCommentInvolvedNotification;
use App\Listeners\SendCommentMentionNotification;
use App\Listeners\SendConstructionReportInvolvedNotification;
use App\Listeners\SendConstructionReportMentionNotification;
use App\Listeners\SendConstructionReportSignedNotification;
use App\Listeners\SendFlowMeterInspectionReportMentionNotification;
use App\Listeners\SendHolidayAllowanceAdjustmentNotification;
use App\Listeners\SendInspectionReportMentionNotification;
use App\Listeners\SendInspectionReportSignedNotification;
use App\Listeners\SendMemoInvolvedNotification;
use App\Listeners\SendMemoMentionNotification;
use App\Listeners\SendServiceReportMentionNotification;
use App\Listeners\SendServiceReportSignedNotification;
use App\Listeners\SendTaskInvolvedNotification;
use App\Listeners\SendTaskMentionNotification;
use App\Models\Accounting;
use App\Notifications\DeliveryNoteSignedNotification;
use App\Notifications\FlowMeterInspectionReportSignedNotification;
use App\Observers\AccountingObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Mail\Events\MessageSent;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        MessageSent::class => [
            LogSentMessageListener::class,
        ],

        AdditionsReportSignedEvent::class => [
            SendAdditionsReportSignedNotification::class,
        ],

        DeliveryNoteSignedEvent::class => [
            DeliveryNoteSignedNotification::class,
        ],

        ConstructionReportSignedEvent::class => [
            SendConstructionReportSignedNotification::class,
        ],

        FlowMeterInspectionReportSignedEvent::class => [
            FlowMeterInspectionReportSignedNotification::class,
        ],

        InspectionReportSignedEvent::class => [
            SendInspectionReportSignedNotification::class,
        ],

        ServiceReportSignedEvent::class => [
            SendServiceReportSignedNotification::class,
        ],
    ];

    /**
     * The model observers for your application.
     *
     * @var array
     */
    //protected $observers = [
    //    Accounting::class => [AccountingObserver::class],
    //];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        SendAdditionsReportInvolvedNotification::class,
        SendAdditionsReportMentionNotification::class,
        SendCommentInvolvedNotification::class,
        SendCommentMentionNotification::class,
        SendConstructionReportInvolvedNotification::class,
        SendConstructionReportMentionNotification::class,
        SendFlowMeterInspectionReportMentionNotification::class,
        SendHolidayAllowanceAdjustmentNotification::class,
        SendInspectionReportMentionNotification::class,
        SendMemoInvolvedNotification::class,
        SendMemoMentionNotification::class,
        SendServiceReportMentionNotification::class,
        SendTaskInvolvedNotification::class,
        SendTaskMentionNotification::class,
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Accounting::observe(AccountingObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
