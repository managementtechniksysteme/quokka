<?php

namespace App\Models;

use App\Traits\FiltersPermissions;
use App\Traits\FiltersSearch;
use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Accounting extends Model
{
    use FiltersPermissions;
    use FiltersSearch;
    use OrdersResults;

    protected $table = 'accounting';

    protected $casts = [
        'service_provided_on' => 'date',
        'service_provided_started_at' => 'datetime:H:i',
        'service_provided_ended_at' => 'datetime:H:i',
        'amount' => 'double',
    ];

    protected $fillable = [
        'service_provided_on',
        'service_provided_started_at',
        'service_provided_ended_at',
        'amount',
        'comment',
        'employee_id',
        'project_id',
        'service_id',
    ];

    protected $orderKeys = [
        'default' => ['service_provided_on', 'service_provided_started_at'],
    ];

    protected $permissionFilters = [
        'accounting.view.own' => ['employee.person_id', '{user}'],
        'accounting.view.other' => ['!employee.person_id', '{user}'],
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'person_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function scopeFilterSearch($query, $params = null)
    {
        if (isset($params['start'])) {
            $query = $query->whereDate('service_provided_on', '>=', $params['start']);
        }

        if (isset($params['end'])) {
            $query = $query->whereDate('service_provided_on', '<=', $params['end']);
        }

        if (isset($params['project_id'])) {
            $query = $query->where('project_id', $params['project_id']);
        }

        if (isset($params['service_id'])) {
            $query = $query->where('service_id', $params['service_id']);
        }

        if (isset($params['employee_id'])) {
            $query = $query->where('employee_id', $params['employee_id']);
        }

        if (isset($params['only_own'])) {
            $query = $query->where('employee_id', Auth::user()->employee_id);
        }

        return $query;
    }

    public static function getReport($params) {
        $specialServiceIds = [
            ApplicationSettings::get()->allowances_service_id,
            ApplicationSettings::get()->overtime_50_service_id,
            ApplicationSettings::get()->overtime_100_service_id,
            ApplicationSettings::get()->time_balance_service_id,
            ApplicationSettings::get()->holiday_service_id,
        ];

        $hourBasedServiceIds = WageService::whereUnit(ApplicationSettings::get()->services_hour_unit)
            ->whereNotIn('id', $specialServiceIds)->select('id')->get()->pluck('id')->toArray();

        $users = User::select(['employee_id', 'username']);

        $accountingFilteredByEmployeeDates = Accounting::select(['service_provided_on', 'project_id', 'employee_id'])
            ->whereIn('employee_id', $params['employee_ids']);

        if(isset($params['start'])) {
            $accountingFilteredByEmployeeDates = $accountingFilteredByEmployeeDates
                ->where('service_provided_on', '>=', $params['start']);
        }
        if(isset($params['end'])) {
            $accountingFilteredByEmployeeDates = $accountingFilteredByEmployeeDates
                ->where('service_provided_on', '<=', $params['end']);
        }
        if(isset($params['project_id'])) {
            $accountingFilteredByEmployeeDates = $accountingFilteredByEmployeeDates
                ->whereProjectId($params['project_id']);
        }
        if(isset($params['service_id'])) {
            $accountingFilteredByEmployeeDates = $accountingFilteredByEmployeeDates
                ->whereServiceId($params['service_id']);
        }
        else {
            $accountingFilteredByEmployeeDates = $accountingFilteredByEmployeeDates
                ->whereIn('service_id', array_merge($hourBasedServiceIds, $specialServiceIds));
        }

        $accountingDistinct = (clone $accountingFilteredByEmployeeDates)->distinct();

        $accountingHourBasedServices = clone $accountingFilteredByEmployeeDates;
        $accountingHourBasedServices = $accountingHourBasedServices
            ->selectRaw('SUM(amount) AS amount_hours')
            ->from('accounting AS accounting_hours')
            ->whereIn('service_id', $hourBasedServiceIds)
            ->groupBy(['service_provided_on', 'project_id', 'employee_id']);

        $accountingAllowances = clone $accountingFilteredByEmployeeDates;
        $accountingAllowances = $accountingAllowances
            ->selectRaw('SUM(amount) AS amount_allowances')
            ->from('accounting AS accounting_allowances')
            ->whereServiceId(ApplicationSettings::get()->allowances_service_id)
            ->groupBy(['service_provided_on', 'project_id', 'employee_id']);

        $accountingOvertime50 = clone $accountingFilteredByEmployeeDates;
        $accountingOvertime50 = $accountingOvertime50
            ->selectRaw('SUM(amount) AS amount_overtime_50')
            ->from('accounting AS accounting_overtime_50')
            ->whereServiceId(ApplicationSettings::get()->overtime_50_service_id)
            ->groupBy(['service_provided_on', 'project_id', 'employee_id']);

        $accountingOvertime100 = clone $accountingFilteredByEmployeeDates;
        $accountingOvertime100 = $accountingOvertime100
            ->selectRaw('SUM(amount) AS amount_overtime_100')
            ->from('accounting AS accounting_overtime_100')
            ->whereServiceId(ApplicationSettings::get()->overtime_100_service_id)
            ->groupBy(['service_provided_on', 'project_id', 'employee_id']);

        $accountingTimeBalance = clone $accountingFilteredByEmployeeDates;
        $accountingTimeBalance = $accountingTimeBalance
            ->selectRaw('SUM(amount) AS amount_time_balance')
            ->from('accounting AS accounting_time_balance')
            ->whereServiceId(ApplicationSettings::get()->time_balance_service_id)
            ->groupBy(['service_provided_on', 'project_id', 'employee_id']);

        $accountingHolidays = clone $accountingFilteredByEmployeeDates;
        $accountingHolidays = $accountingHolidays
            ->selectRaw('SUM(amount) AS amount_holidays')
            ->from('accounting AS accounting_holidays')
            ->whereServiceId(ApplicationSettings::get()->holiday_service_id)
            ->groupBy(['service_provided_on', 'project_id', 'employee_id']);

        $accounting = DB::table('projects')
            ->selectRaw(
                'projects.name as project, ' .
                'accounting.service_provided_on as date'
            )
            ->addSelect([
                'users.username',
                'amount_hours',
                'amount_allowances',
                'amount_overtime_50',
                'amount_overtime_100',
                'amount_time_balance',
                'amount_holidays',
            ])
            ->joinSub(
                $accountingDistinct, 'accounting', 'projects.id', '=', 'accounting.project_id'
            )
            ->joinSub(
                $users, 'users', 'accounting.employee_id', '=', 'users.employee_id'
            )
            ->leftJoinSub($accountingHourBasedServices, 'accounting_hours', function($join) {
                $join->on('accounting.service_provided_on', '=', 'accounting_hours.service_provided_on')
                    ->on('accounting.project_id', '=', 'accounting_hours.project_id')
                    ->on('accounting.employee_id', '=', 'accounting_hours.employee_id');
            })
            ->leftJoinSub($accountingAllowances, 'accounting_allowances', function($join) {
                $join->on('accounting.service_provided_on', '=', 'accounting_allowances.service_provided_on')
                    ->on('accounting.project_id', '=', 'accounting_allowances.project_id')
                    ->on('accounting.employee_id', '=', 'accounting_allowances.employee_id');
            })
            ->leftJoinSub($accountingOvertime50, 'accounting_overtime_50', function($join) {
                $join->on('accounting.service_provided_on', '=', 'accounting_overtime_50.service_provided_on')
                    ->on('accounting.project_id', '=', 'accounting_overtime_50.project_id')
                    ->on('accounting.employee_id', '=', 'accounting_overtime_50.employee_id');
            })
            ->leftJoinSub($accountingOvertime100, 'accounting_overtime_100', function($join) {
                $join->on('accounting.service_provided_on', '=', 'accounting_overtime_100.service_provided_on')
                    ->on('accounting.project_id', '=', 'accounting_overtime_100.project_id')
                    ->on('accounting.employee_id', '=', 'accounting_overtime_100.employee_id');
            })
            ->leftJoinSub($accountingTimeBalance, 'accounting_time_balance', function($join) {
                $join->on('accounting.service_provided_on', '=', 'accounting_time_balance.service_provided_on')
                    ->on('accounting.project_id', '=', 'accounting_time_balance.project_id')
                    ->on('accounting.employee_id', '=', 'accounting_time_balance.employee_id');
            })
            ->leftJoinSub($accountingHolidays, 'accounting_holidays', function($join) {
                $join->on('accounting.service_provided_on', '=', 'accounting_holidays.service_provided_on')
                    ->on('accounting.project_id', '=', 'accounting_holidays.project_id')
                    ->on('accounting.employee_id', '=', 'accounting_holidays.employee_id');
            })
            ->orderBy('accounting.service_provided_on')
            ->orderBy('projects.name')
            ->orderBy('users.username');

        return $accounting->get();
    }
}
