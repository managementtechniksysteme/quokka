<?php

namespace App\Providers;

use App\Events\AdditionsReportSignedEvent;
use App\Events\ConstructionReportSignedEvent;
use App\Events\InspectionReportSignedEvent;
use App\Events\ServiceReportSignedEvent;
use App\Listeners\SendAdditionsReportMentionNotification;
use App\Listeners\SendAdditionsReportSignedNotification;
use App\Listeners\SendCommentInvolvedNotification;
use App\Listeners\SendCommentMentionNotification;
use App\Listeners\SendConstructionReportMentionNotification;
use App\Listeners\SendConstructionReportSignedNotification;
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
use App\Observers\AccountingObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        AdditionsReportSignedEvent::class => [
            SendAdditionsReportSignedNotification::class,
        ],

        ConstructionReportSignedEvent::class => [
            SendConstructionReportSignedNotification::class,
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
        SendAdditionsReportMentionNotification::class,
        SendCommentInvolvedNotification::class,
        SendCommentMentionNotification::class,
        SendConstructionReportMentionNotification::class,
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
     *
     * @return void
     */
    public function boot()
    {
        Accounting::observe(AccountingObserver::class);
    }
}
