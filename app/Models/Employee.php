<?php

namespace App\Models;

use App\Support\GlobalSearch\GlobalSearchResult;
use App\Traits\FiltersLatestChanges;
use App\Traits\FiltersSearch;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Employee extends Model
{
    use FiltersLatestChanges;
    use FiltersSearch;
    use HasFactory;

    const DASHBOARD_CACHE_TAG_PREFIX = 'dashboard';
    const DASHBOARD_CACHE_TTL = 60;

    protected $primaryKey = 'person_id';
    public $incrementing = false;

    protected $casts = [
        'person_id' => 'int',
        'entered_on' => 'date',
        'left_on' => 'date',
        'holidays' => 'double',
    ];

    protected $fillable = [
        'person_id', 'entered_on', 'left_on', 'holidays',
    ];

    protected $filterFields = [
        'person.first_name', 'person.last_name', 'user.username'
    ];

    protected $filterKeys = [
        'name:(.*)' => ['hasraw' => ['person', 'concat(first_name, " ", last_name) like "%{value}%"', 'concat(first_name, " ", last_name) not like "%{value}%"']],
        'n:(.*)' => ['hasraw' => ['person', 'concat(first_name, " ", last_name) like "%{value}%"', 'concat(first_name, " ", last_name) not like "%{value}%"']],
        'benutzer:(.*)' => ['user.username', '{value}'],
        'b:(.*)' => ['user.username', '{value}'],
    ];

    public static function filterGlobalSearch(string $query, ?int $latestQuantity = null) : Collection
    {
        return Employee::filterSearch($query)
            ->with('person')
            ->when($latestQuantity && $latestQuantity > 0, function ($query) use ($latestQuantity) {
                return $query->latest('updated_at')->limit($latestQuantity);
            })
            ->get()
            ->map(function(Employee $employee) {
                return new GlobalSearchResult(
                    Employee::class,
                    'Mitarbeiter',
                    $employee->id,
                    $employee->person->name,
                    route('employees.show', $employee),
                    $employee->created_at,
                    $employee->updated_at,
                );
            });
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'employee_id')->withTrashed();
    }

    public function tasksResponsibleFor()
    {
        return $this->hasMany(Task::class, 'employee_id');
    }

    public function tasksInvolvedIn()
    {
        return $this->morphedByMany(Task::class, 'employeeable', null, 'employee_id', 'employeeable_id')->wherePivot('employee_type', 'involved');
    }

    public function taskComments()
    {
        return $this->hasMany(TaskComment::class, 'employee_id');
    }

    public function composedMemos()
    {
        return $this->hasMany(Memo::class);
    }

    public function serviceReports()
    {
        return $this->hasMany(ServiceReport::class, 'employee_id');
    }

    public function additionsReports() {
        return $this->hasMany(AdditionsReport::class, 'employee_id');
    }

    public function additionsReportsInvolvedIn() {
        return $this->morphedByMany(AdditionsReport::class, 'employeeable', null, 'employee_id', 'employeeable_id')->wherePivot('employee_type', 'involved');
    }

    public function inspectionReports() {
        return $this->hasMany(InspectionReport::class, 'employee_id');
    }

    public function flowMeterInspectionReports() {
        return $this->hasMany(FlowMeterInspectionReport::class, 'employee_id');
    }

    public function constructionReports() {
        return $this->hasMany(ConstructionReport::class, 'employee_id');
    }

    public function constructionReportsInvolvedIn() {
        return $this->morphedByMany(ConstructionReport::class, 'employeeable', null, 'employee_id', 'employeeable_id')->wherePivot('employee_type', 'involved');
    }

    public function accounting()
    {
        return $this->hasMany(Accounting::class, 'employee_id');
    }

    public function logbook()
    {
        return $this->hasMany(Logbook::class, 'employee_id');
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'employee_id');
    }

    public function isCurrentlyOnHoliday()
    {
        $currentHolidayAccounting = $this->accounting()
            ->whereServiceProvidedOn(Carbon::today())
            ->whereServiceId(ApplicationSettings::get()->holiday_service_id);

        return $currentHolidayAccounting->exists();
    }

    public function getMTDHourlyBasedServicesAttribute()
    {
        $specialServiceIds = [
            ApplicationSettings::get()->allowances_service_id,
            ApplicationSettings::get()->overtime_50_service_id,
            ApplicationSettings::get()->overtime_100_service_id,
            ApplicationSettings::get()->time_balance_service_id,
            ApplicationSettings::get()->holiday_service_id,
        ];

        $hourBasedServiceIds = WageService::whereUnit(ApplicationSettings::get()->services_hour_unit)
            ->whereNotIn('id', $specialServiceIds)->select('id')->get()->pluck('id')->toArray();

        return $this->getMTDServiceSum($hourBasedServiceIds);
    }

    public function getMTDAllowancesAttribute()
    {
        $allowanceServiceId = ApplicationSettings::get()->allowances_service_id;

        if($allowanceServiceId === null) {
            return null;
        }

        return $this->getCache()
            ->remember($this->getDashboardCacheKeyName('mtd-allowances'), static::DASHBOARD_CACHE_TTL,
                function() use ($allowanceServiceId) {
                    return $this->getMTDServiceSum($allowanceServiceId);
                }
            );
    }

    public function getMTDAllowancesInCurrencyAttribute()
    {
        if($this->mtd_allowances === null) {
            return null;
        }

        return $this->mtd_allowances * ApplicationSettings::get()->allowancesService->costs;
    }

    public function getMTDOvertime50Attribute()
    {
        $overtime50ServiceId = ApplicationSettings::get()->overtime_50_service_id;

        if($overtime50ServiceId === null) {
            return null;
        }

        return $this->getCache()
            ->tags($this->getDashboardCacheTag())
            ->remember($this->getDashboardCacheKeyName('mtd-overtime-50'), static::DASHBOARD_CACHE_TTL,
                function() use ($overtime50ServiceId) {
                    return $this->getMTDServiceSum($overtime50ServiceId);
                }
            );
    }

    public function getMTDOvertime100Attribute()
    {
        $overtime100ServiceId = ApplicationSettings::get()->overtime_100_service_id;

        if($overtime100ServiceId === null) {
            return null;
        }

        return $this->getCache()
            ->tags($this->getDashboardCacheTag())
            ->remember($this->getDashboardCacheKeyName('mtd-overtime-100'), static::DASHBOARD_CACHE_TTL,
                function() use ($overtime100ServiceId) {
                    return $this->getMTDServiceSum($overtime100ServiceId);
                }
            );
    }

    public function getMTDOvertimeAttribute()
    {
        if(ApplicationSettings::get()->overtime_50_service_id === null &&
            ApplicationSettings::get()->overtime_100_service_id === null) {
            return null;
        }

        return $this->mtd_overtime_50 ?? 0 + $this->mtd_overtime_100 ?? 0;
    }

    private function getMTDServiceSum(int|array $serviceIds)
    {
        $today = Carbon::today();
        $firstOfMonth = Carbon::today()->firstOfMonth();

        $serviceIds = is_array($serviceIds) ? $serviceIds : [$serviceIds];

        return (double)$this->accounting()
            ->whereIn('service_id', $serviceIds)
            ->whereBetween('service_provided_on', [$firstOfMonth, $today])
            ->sum('amount');
    }

    public function getMTDCreatedTasksResponsibleForAttribute()
    {
        $today = Carbon::today();
        $firstOfMonth = Carbon::today()->firstOfMonth();

        return $this->getCache()
            ->tags($this->getDashboardCacheTag())
            ->remember($this->getDashboardCacheKeyName('mtd-created-tasks-responsible-for'), static::DASHBOARD_CACHE_TTL,
                function() use ($today, $firstOfMonth) {
                    return $this->tasksResponsibleFor()
                        ->whereBetween('tasks.created_at', [$firstOfMonth, $today])->count();
                }
            );
    }

    public function getMTDCreatedTasksInvolvedInAttribute()
    {
        $today = Carbon::today();
        $firstOfMonth = Carbon::today()->firstOfMonth();

        return $this->getCache()
            ->tags($this->getDashboardCacheTag())
            ->remember($this->getDashboardCacheKeyName('mtd-created-tasks-involved-in'), static::DASHBOARD_CACHE_TTL,
                function() use ($today, $firstOfMonth) {
                    return $this->tasksInvolvedIn()
                        ->whereBetween('tasks.created_at', [$firstOfMonth, $today])->count();
                }
            );
    }

    public function getMTDCreatedTasksAttribute()
    {
        return $this->mtd_created_tasks_responsible_for + $this->mtd_created_tasks_involved_in;
    }

    public function getMTDFinishedTasksResponsibleForAttribute()
    {
        $today = Carbon::today();
        $firstOfMonth = Carbon::today()->firstOfMonth();

        return $this->getCache()
            ->tags($this->getDashboardCacheTag())
            ->remember($this->getDashboardCacheKeyName('mtd-finished-tasks-responsible-for'), static::DASHBOARD_CACHE_TTL,
                function() use ($today, $firstOfMonth) {
                    return $this->tasksResponsibleFor()
                        ->whereStatus('finished')
                        ->whereBetween('ends_on', [$firstOfMonth, $today])->count();
                }
            );
    }

    public function getMTDFinishedTasksInvolvedInAttribute()
    {
        $today = Carbon::today();
        $firstOfMonth = Carbon::today()->firstOfMonth();

        return $this->getCache()
            ->tags($this->getDashboardCacheTag())
            ->remember($this->getDashboardCacheKeyName('mtd-finished-tasks-involved-in'), static::DASHBOARD_CACHE_TTL,
                function() use ($today, $firstOfMonth) {
                    return $this->tasksInvolvedIn()
                        ->whereStatus('finished')
                        ->whereBetween('ends_on', [$firstOfMonth, $today])->count();
                }
            );
    }

    public function getMTDFinishedTasksAttribute()
    {
        return $this->mtd_finished_tasks_responsible_for + $this->mtd_finished_tasks_involved_in;
    }

    public function getOverdueTasksResponsibleForAttribute()
    {
        return $this->tasksResponsibleFor
            ->filter(function ($task) {
                return $task->isOverdue();
            })
            ->count();
    }

    public function getOverdueTasksInvolvedInAttribute()
    {
        return $this->tasksInvolvedIn
            ->filter(function ($task) {
                return $task->isOverdue();
            })
            ->count();
    }

    public function getOverdueTasksAttribute()
    {
        return $this->overdue_tasks_responsible_for + $this->overdue_tasks_involved_in;
    }

    public function getDueSoonTasksResponsibleForAttribute()
    {
        return $this->tasksResponsibleFor
            ->filter(function ($task) {
            return $task->isDueSoon();
            })
            ->count();
    }

    public function getDueSoonTasksInvolvedInAttribute()
    {
        return $this->tasksInvolvedIn
            ->filter(function ($task) {
                return $task->isDueSoon();
            })->count();
    }

    public function getDueSoonTasksAttribute()
    {
        return $this->due_soon_tasks_responsible_for + $this->due_soon_tasks_involved_in;
    }

    public function getMTDNewServiceReportsAttribute()
    {
        $now = Carbon::now();
        $firstOfMonth = Carbon::today()->firstOfMonth();

        return $this->serviceReports()
            ->whereStatus('new')
            ->whereBetween('created_at', [$firstOfMonth, $now])
            ->count();
    }

    public function getNewServiceReportsAttribute()
    {
        return $this->serviceReports()
            ->whereStatus('new')
            ->count();
    }

    public function getMTDNewAdditionsReportsAttribute()
    {
        $now = Carbon::now();
        $firstOfMonth = Carbon::today()->firstOfMonth();

        return $this->additionsReports()
            ->whereStatus('new')
            ->whereBetween('created_at', [$firstOfMonth, $now])
            ->count();
    }

    public function getNewAdditionsReportsAttribute()
    {
        return $this->additionsReports()
            ->whereStatus('new')
            ->count();
    }

    public function getNewAdditionsReportsInvolvedInAttribute()
    {
        return $this->additionsReportsInvolvedIn()
            ->whereStatus('new')
            ->count();
    }

    public function getMTDNewInspectionReportsAttribute()
    {
        $now = Carbon::now();
        $firstOfMonth = Carbon::today()->firstOfMonth();

        return $this->inspectionReports()
            ->whereStatus('new')
            ->whereBetween('created_at', [$firstOfMonth, $now])
            ->count();
    }

    public function getNewInspectionReportsAttribute()
    {
        return $this->inspectionReports()
            ->whereStatus('new')
            ->count();
    }

    public function getMTDNewFlowMeterInspectionReportsAttribute()
    {
        $now = Carbon::now();
        $firstOfMonth = Carbon::today()->firstOfMonth();

        return $this->flowMeterInspectionReports()
            ->whereStatus('new')
            ->whereBetween('created_at', [$firstOfMonth, $now])
            ->count();
    }

    public function getNewFlowMeterInspectionReportsAttribute()
    {
        return $this->flowMeterInspectionReports()
            ->whereStatus('new')
            ->count();
    }

    public function getMTDNewConstructionReportsAttribute()
    {
        $now = Carbon::now();
        $firstOfMonth = Carbon::today()->firstOfMonth();

        return $this->constructionReports()
            ->whereStatus('new')
            ->whereBetween('created_at', [$firstOfMonth, $now])
            ->count();
    }

    public function getNewConstructionReportsAttribute()
    {
        return $this->constructionReports()
            ->whereStatus('new')
            ->count();
    }

    public function getNewConstructionReportsInvolvedInAttribute()
    {
        return $this->constructionReportsInvolvedIn()
            ->whereStatus('new')
            ->count();
    }

    public function getMTDKilometresAttribute()
    {
        $today = Carbon::today();
        $firstOfMonth = Carbon::today()->firstOfMonth();

        return $this->getCache()
            ->tags($this->getDashboardCacheTag())
            ->remember($this->getDashboardCacheKeyName('mtd-kilometres'), static::DASHBOARD_CACHE_TTL,
                function() use ($today, $firstOfMonth) {
                    return (int)$this->logbook()
                        ->whereBetween('driven_on', [$firstOfMonth, $today])
                        ->sum('driven_kilometres');
                }
            );
    }

    public function getMTDCompanyKilometresAttribute()
    {
        $today = Carbon::today();
        $firstOfMonth = Carbon::today()->firstOfMonth();

        return $this->getCache()
            ->tags($this->getDashboardCacheTag())
            ->remember($this->getDashboardCacheKeyName('mtd-company-kilometres'), static::DASHBOARD_CACHE_TTL,
                function() use ($today, $firstOfMonth) {
                    return (int)$this->logbook()
                        ->whereHas('vehicle', function (Builder $query) {
                            $query->where('private', false);
                        })
                        ->whereBetween('driven_on', [$firstOfMonth, $today])
                        ->sum('driven_kilometres');
                }
            );
    }

    public function getPrivateKilometres($start, $end)
    {
        return (int)$this->logbook()
            ->whereHas('vehicle', function (Builder $query) {
                $query->where('private', true);
            })
            ->when($start, function ($query) use ($start) {
                $query->where('driven_on', '>=', $start);
            })
            ->when($end, function ($query) use ($end) {
                $query->where('driven_on', '<=', $end);
            })
            ->sum('driven_kilometres');
    }

    public function getMTDPrivateKilometresAttribute()
    {
        $today = Carbon::today();
        $firstOfMonth = Carbon::today()->firstOfMonth();

        return $this->getCache()
            ->tags($this->getDashboardCacheTag())
            ->remember($this->getDashboardCacheKeyName('mtd-private-kilometres'), static::DASHBOARD_CACHE_TTL,
                function() use ($today, $firstOfMonth) {
                    return $this->getPrivateKilometres($firstOfMonth, $today);
                }
            );
    }

    public function getMTDPrivateKilometresInCurrencyAttribute()
    {
        return $this->mtd_private_kilometres * ApplicationSettings::get()->kilometre_costs;
    }

    public function clearDashboardCache()
    {
        $this->getCache()->flush();
    }

    private function getCache()
    {
        return Cache::store('array')->tags($this->getDashboardCacheTag());
    }

    private function getDashboardCacheTag() {
        return static::DASHBOARD_CACHE_TAG_PREFIX . ':' . $this->person_id;
    }

    private function getDashboardCacheKeyName(string $name) {
        return $this->getDashboardCacheTag() . ':' . $name;
    }
}
