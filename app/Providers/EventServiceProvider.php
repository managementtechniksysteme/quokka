<?php

namespace App\Providers;

use App\Events\ServiceReportSignedEvent;
use App\Listeners\SendCommentMentionNotification;
use App\Listeners\SendMemoInvolvedNotification;
use App\Listeners\SendMemoMentionNotification;
use App\Listeners\SendServiceReportMentionNotification;
use App\Listeners\SendServiceReportSignedNotification;
use App\Listeners\SendTaskInvolvedNotification;
use App\Listeners\SendTaskMentionNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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

        ServiceReportSignedEvent::class => [
            SendServiceReportSignedNotification::class,
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        SendCommentMentionNotification::class,
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
        //
    }
}
