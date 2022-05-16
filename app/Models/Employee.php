<?php

namespace App\Models;

use App\Traits\FiltersSearch;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use FiltersSearch;
    use HasFactory;

    protected $casts = [
        'person_id' => 'int',
        'entered_on' => 'date',
        'left_on' => 'date',
        'holidays' => 'double',
    ];

    protected $fillable = [
        'person_id', 'entered_on', 'left_on', 'holidays',
    ];

    protected $filterFields = [];

    protected $filterKeys = [
        'name:(.*)' => ['hasraw' => ['person', 'concat(first_name, " ", last_name) like "%{value}%"', 'concat(first_name, " ", last_name) not like "%{value}%"']],
        'n:(.*)' => ['hasraw' => ['person', 'concat(first_name, " ", last_name) like "%{value}%"', 'concat(first_name, " ", last_name) not like "%{value}%"']],
        'benutzer:(.*)' => ['user.username', '{value}'],
        'b:(.*)' => ['user.username', '{value}'],
    ];

    protected $primaryKey = 'person_id';
    public $incrementing = false;

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

    public function accounting()
    {
        return $this->hasMany(Accounting::class, 'employee_id');
    }

    public function logbook()
    {
        return $this->hasMany(Logbook::class, 'employee_id');
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

        return $this->getMTDServiceSum($allowanceServiceId);
    }

    public function getMTDAllowancesInCurrencyAttribute()
    {
        $allowanceServiceId = ApplicationSettings::get()->allowances_service_id;

        if($allowanceServiceId === null) {
            return null;
        }

        return $this->getMTDServiceSum($allowanceServiceId) * ApplicationSettings::get()->allowancesService->costs;
    }

    public function getMTDOvertime50Attribute()
    {
        $overtime50ServiceId = ApplicationSettings::get()->overtime_50_service_id;

        if($overtime50ServiceId === null) {
            return null;
        }

        return $this->getMTDServiceSum($overtime50ServiceId);
    }

    public function getMTDOvertime100Attribute()
    {
        $overtime100ServiceId = ApplicationSettings::get()->overtime_100_service_id;

        if($overtime100ServiceId === null) {
            return null;
        }

        return $this->getMTDServiceSum($overtime100ServiceId);
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

        return $this->accounting()
            ->whereIn('service_id', $serviceIds)
            ->whereBetween('service_provided_on', [$firstOfMonth, $today])
            ->sum('amount');
    }

    public function getMTDCreatedTasksResponsibleForAttribute()
    {
        $today = Carbon::today();
        $firstOfMonth = Carbon::today()->firstOfMonth();

        return $this->tasksResponsibleFor()
            ->whereBetween('tasks.created_at', [$firstOfMonth, $today])->count();
    }

    public function getMTDCreatedTasksInvolvedInAttribute()
    {
        $today = Carbon::today();
        $firstOfMonth = Carbon::today()->firstOfMonth();

        return $this->tasksInvolvedIn()
            ->whereBetween('tasks.created_at', [$firstOfMonth, $today])->count();
    }

    public function getMTDCreatedTasksAttribute()
    {
        return $this->mtd_created_tasks_responsible_for + $this->mtd_created_tasks_involved_in;
    }

    public function getMTDFinishedTasksResponsibleForAttribute()
    {
        $today = Carbon::today();
        $firstOfMonth = Carbon::today()->firstOfMonth();

        return $this->tasksResponsibleFor()
            ->whereStatus('finished')
            ->whereBetween('ends_on', [$firstOfMonth, $today])->count();
    }

    public function getMTDFinishedTasksInvolvedInAttribute()
    {
        $today = Carbon::today();
        $firstOfMonth = Carbon::today()->firstOfMonth();

        return $this->tasksInvolvedIn()
            ->whereStatus('finished')
            ->whereBetween('ends_on', [$firstOfMonth, $today])->count();
    }

    public function getMTDFinishedTasksAttribute()
    {
        return $this->mtd_finished_tasks_responsible_for + $this->mtd_finished_tasks_involved_in;
    }

    public function getOverdueTasksResponsibleForAttribute()
    {
        return $this->tasksResponsibleFor()
            ->get()
            ->filter(function ($task) {
                return $task->isOverdue();
            })
            ->count();
    }

    public function getOverdueTasksInvolvedInAttribute()
    {
        return $this->tasksInvolvedIn()
            ->get()
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
        return $this->tasksResponsibleFor()
            ->get()
            ->filter(function ($task) {
            return $task->isDueSoon();
            })
            ->count();
    }

    public function getDueSoonTasksInvolvedInAttribute()
    {
        return $this->tasksInvolvedIn()
            ->get()
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
        $today = Carbon::today();
        $firstOfMonth = Carbon::today()->firstOfMonth();

        return $this->serviceReports()
            ->whereStatus('new')
            ->whereBetween('created_at', [$firstOfMonth, $today])
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
        return 'NA';
    }

    public function getNewAdditionsReportsAttribute()
    {
        return 'NA';
    }

    public function getMTDNewInspectionReportsAttribute()
    {
        return 'NA';
    }

    public function getNewInspectionReportsAttribute()
    {
        return 'NA';
    }

    public function getMTDNewdBuildDayReportsAttribute()
    {
        return 'NA';
    }

    public function getNewBuildDayReportsAttribute()
    {
        return 'NA';
    }
}
