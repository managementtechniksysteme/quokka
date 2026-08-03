<?php

namespace App\Providers;

use App\Helpers\DateTime;
use App\Helpers\NotificationHelper;
use App\Http\ViewComposers\Error500ViewComposer;
use App\Listeners\LogSentMessageListener;
use App\Models\Accounting;
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
        Paginator::useBootstrapFive();

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
        Gate::define('share-target', fn ($user) => $user->can('notes.create'));
        Gate::define('tools-scanqr', fn ($user) => $user->can('tools.scanqr'));
        Gate::define('tools-viewlatestchanges', fn ($user) => $user->can('tools.viewlatestchanges'));
        Gate::define('tools-viewsentemails', fn ($user) => $user->can('tools.viewsentemails'));
        Gate::define('tools-viewmetrics', fn ($user) => $user->can('tools.viewmetrics'));
        Gate::define('tools-viewexceptions', fn ($user) => $user->can('exceptions.view'));
        Gate::define('tools-deleteexceptions', fn ($user) => $user->can('exceptions.delete'));
    }

    private function configureEvents(): void
    {
        Accounting::observe(AccountingObserver::class);

        // LogSentMessageListener::handle() has no type-hinted parameter, so
        // Laravel's event auto-discovery can't infer which event it maps to.
        // Every other app/Listeners class is discovered automatically.
        Event::listen(MessageSent::class, LogSentMessageListener::class);
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
