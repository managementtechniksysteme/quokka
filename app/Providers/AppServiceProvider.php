<?php

namespace App\Providers;

use App\Events\AdditionsReportSignedEvent;
use App\Events\ConstructionReportSignedEvent;
use App\Events\DeliveryNoteSignedEvent;
use App\Events\FlowMeterInspectionReportSignedEvent;
use App\Events\InspectionReportSignedEvent;
use App\Events\ServiceReportSignedEvent;
use App\Helpers\DateTime;
use App\Helpers\NotificationHelper;
use App\Http\ViewComposers\Error500ViewComposer;
use App\Listeners\LogSentMessageListener;
use App\Listeners\SendAdditionsReportInvolvedNotification;
use App\Listeners\SendAdditionsReportMentionNotification;
use App\Listeners\SendAdditionsReportSignedNotification;
use App\Listeners\SendCommentInvolvedNotification;
use App\Listeners\SendCommentMentionNotification;
use App\Listeners\SendConstructionReportInvolvedNotification;
use App\Listeners\SendConstructionReportMentionNotification;
use App\Listeners\SendConstructionReportSignedNotification;
use App\Listeners\SendDeliveryNoteSignedNotification;
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
use App\Policies\NotificationPolicy;
use App\Policies\RolePolicy;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Http\Request;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->configureApp();
        $this->configureAliases();
        $this->configureAuth();
        $this->configureEvents();
        $this->configureRateLimiting();
        $this->configureViews();
    }

    private function configureApp(): void
    {
        Paginator::useBootstrap();

        Carbon::setLocale(config('app.locale'));
        Carbon::setToStringFormat('d.m.Y');
    }

    private function configureAliases(): void
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('DateTimeHelpers', DateTime::class);
        $loader->alias('Email', \App\Helpers\Email::class);
        $loader->alias('Html', \App\Helpers\Html::class);
        $loader->alias('Latex', \App\Helpers\Latex::class);
        $loader->alias('NotificationHelper', NotificationHelper::class);
        $loader->alias('Number', \App\Helpers\Number::class);
    }

    private function configureAuth(): void
    {
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(DatabaseNotification::class, NotificationPolicy::class);

        Gate::define('application-settings-update', fn ($user) => $user->can('application-settings.update.general'));
        Gate::define('finances-view', fn ($user) => $user->can('finances.view'));
        Gate::define('finances-createpdf', fn ($user) => $user->can('finances.createpdf'));
        Gate::define('help-view', fn ($user) => $user->can('help.view'));
        Gate::define('tools-scanqr', fn ($user) => $user->can('tools.scanqr'));
        Gate::define('tools-viewlatestchanges', fn ($user) => $user->can('tools.viewlatestchanges'));
        Gate::define('tools-viewsentemails', fn ($user) => $user->can('tools.viewsentemails'));
        Gate::define('tools-viewexceptions', fn ($user) => $user->can('exceptions.view'));
        Gate::define('tools-deleteexceptions', fn ($user) => $user->can('exceptions.delete'));
    }

    private function configureEvents(): void
    {
        Accounting::observe(AccountingObserver::class);

        Event::listen(MessageSent::class, LogSentMessageListener::class);
        Event::listen(AdditionsReportSignedEvent::class, SendAdditionsReportSignedNotification::class);
        Event::listen(DeliveryNoteSignedEvent::class, SendDeliveryNoteSignedNotification::class);
        Event::listen(ConstructionReportSignedEvent::class, SendConstructionReportSignedNotification::class);
        Event::listen(FlowMeterInspectionReportSignedEvent::class, FlowMeterInspectionReportSignedNotification::class);
        Event::listen(InspectionReportSignedEvent::class, SendInspectionReportSignedNotification::class);
        Event::listen(ServiceReportSignedEvent::class, SendServiceReportSignedNotification::class);

        Event::subscribe(SendAdditionsReportInvolvedNotification::class);
        Event::subscribe(SendAdditionsReportMentionNotification::class);
        Event::subscribe(SendCommentInvolvedNotification::class);
        Event::subscribe(SendCommentMentionNotification::class);
        Event::subscribe(SendConstructionReportInvolvedNotification::class);
        Event::subscribe(SendConstructionReportMentionNotification::class);
        Event::subscribe(SendFlowMeterInspectionReportMentionNotification::class);
        Event::subscribe(SendHolidayAllowanceAdjustmentNotification::class);
        Event::subscribe(SendInspectionReportMentionNotification::class);
        Event::subscribe(SendMemoInvolvedNotification::class);
        Event::subscribe(SendMemoMentionNotification::class);
        Event::subscribe(SendServiceReportMentionNotification::class);
        Event::subscribe(SendTaskInvolvedNotification::class);
        Event::subscribe(SendTaskMentionNotification::class);
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    private function configureViews(): void
    {
        View::composer('errors::500', Error500ViewComposer::class);

        Blade::directive('version', function ($format) {
            return "<?php echo sprintf('v%s.%s.%s-%s', config('version.major'), config('version.minor'), config('version.patch'), config('version.commit')); ?>";
        });
    }
}
