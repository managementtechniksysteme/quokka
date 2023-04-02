<?php

namespace App\Providers;

use App\Policies\ExceptionPolicy;
use App\Policies\RolePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Role::class => RolePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('application-settings-update', function ($user) {
            return $user->can('application-settings.update.general');
        });

        Gate::define('finances-view', function ($user) {
            return $user->can('finances.view');
        });

        Gate::define('finances-createpdf', function ($user) {
            return $user->can('finances.createpdf');
        });

        Gate::define('help-view', function ($user) {
            return $user->can('help.view');
        });

        Gate::define('tools-scanqr', function ($user) {
            return $user->can('tools.scanqr');
        });

        Gate::define('tools-viewlatestchanges', function ($user) {
            return $user->can('tools.viewlatestchanges');
        });

        Gate::define('tools-viewsentemails', function ($user) {
            return $user->can('tools.viewsentemails');
        });

        Gate::define('tools-viewexceptions', function ($user) {
            return $user->can('exceptions.view');
        });

        Gate::define('tools-deleteexceptions', function ($user) {
            return $user->can('exceptions.delete');
        });
    }
}
