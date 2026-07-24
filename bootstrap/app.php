<?php

use App\Jobs\AdjustHolidayAllowanceJob;
use App\Jobs\PruneSentEmailsJob;
use App\Jobs\SendNotificationSummaryJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo('/home');
        $middleware->alias([
            'reauth' => \App\Http\Middleware\Reauthenticate::class,
            'ability' => \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class,
            'abilities' => \Laravel\Sanctum\Http\Middleware\CheckAbilities::class,
        ]);
        // The Web Share Target API's POST is constructed by the OS/browser
        // directly from the manifest's share_target declaration -- there's
        // no page, no JS, so no way for it to carry our CSRF token. This is
        // inherent to the feature (every PWA share target needs the same
        // exemption), not an oversight; the route itself still requires an
        // authenticated session.
        $middleware->validateCsrfTokens(except: ['share-target']);
        $middleware->trustProxies(
            at: '*',
            headers: Request::HEADER_X_FORWARDED_FOR
                | Request::HEADER_X_FORWARDED_HOST
                | Request::HEADER_X_FORWARDED_PORT
                | Request::HEADER_X_FORWARDED_PROTO,
        );
    })
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('cache:prune-stale-tags')->hourly();
        $schedule->command('model:prune')->daily();
        $schedule->command('sanctum:prune-expired --hours=24')->daily();
        $schedule->job(new PruneSentEmailsJob)->daily();
        $schedule->job(new AdjustHolidayAllowanceJob)->daily();
        $schedule->job(new SendNotificationSummaryJob)->dailyAt('07:00');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
