<?php

namespace App\Providers;

use App\Models\AdditionsReport;
use App\Models\ConstructionReport;
use App\Models\InspectionReport;
use App\Policies\AdditionsReportPolicy;
use App\Policies\ConstructionReportPolicy;
use App\Policies\ExceptionPolicy;
use App\Policies\InspectionReportPolicy;
use App\Policies\RolePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Role::class => RolePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('application-settings-update', function ($user) {
            return $user->can('application-settings.update.general');
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

        Gate::define('tools-viewexceptions', function ($user) {
            return $user->can('exceptions.view');
        });

        Gate::define('tools-deleteexceptions', function ($user) {
            return $user->can('exceptions.delete');
        });
    }
}
