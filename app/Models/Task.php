<?php

namespace App\Models;

use App\Support\GlobalSearch\FiltersGlobalSearch;
use App\Support\GlobalSearch\GlobalSearchResult;
use App\Traits\FiltersLatestChanges;
use App\Traits\FiltersPermissions;
use App\Traits\FiltersSearch;
use App\Traits\HasAttachments;
use App\Traits\OrdersResults;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;

class Task extends Model implements FiltersGlobalSearch, HasMedia
{
    use FiltersLatestChanges;
    use FiltersSearch;
    use FiltersPermissions;
    use HasAttachments;
    use LogsActivity;
    use OrdersResults;

    protected $casts = [
        'starts_on' => 'date',
        'ends_on' => 'date',
        'due_on' => 'date',
        'private' => 'boolean',
    ];

    protected $fillable = [
        'name', 'starts_on', 'ends_on', 'due_on', 'private', 'priority', 'status', 'billed', 'comment', 'project_id',
        'employee_id',
    ];

    protected $filterFields = [
        'name',
        'project.name',
        'project.company.name',
        'responsibleEmployee.person.first_name',
        'responsibleEmployee.person.last_name',
        'responsibleEmployee.user.username',
        'involvedEmployees.person.first_name',
        'involvedEmployees.person.last_name',
        'involvedEmployees.user.username',
    ];

    protected $filterKeys = [
        'ist:privat' => ['private', true],
        'ist:niedrig' => ['priority', 'low'],
        'ist:mittel' => ['priority', 'medium'],
        'ist:hoch' => ['priority', 'high'],
        'ist:neu' => ['status', 'new'],
        'ist:in_bearbeitung' => ['status', 'in progress'],
        'ist:ib' => ['status', 'in progress'],
        'ist:erledigt' => ['status', 'finished'],
        'ist:verrechnet' => ['billed', 'yes'],
        'ist:nicht_verrechnet' => ['billed', 'no'],
        'ist:nv' => ['billed', 'no'],
        'ist:garantie' => ['billed', 'warranty'],
        'ist:überfällig' => ['raw' => ['due_on < curdate() and status != "finished"', 'due_on <= curdate() or (due_on > curdate() and status = "finished")']],
        'projekt:(.*)' => ['project.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'p:(.*)' => ['project.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'firma:(.*)' => ['project.company.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'f:(.*)' => ['project.company.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'verantwortlich:(.*)' => ['responsibleEmployee.user.username', '{value}'],
        'v:(.*)' => ['responsibleEmployee.user.username', '{value}'],
        'beteiligt:(.*)' => ['involvedEmployees.user.username', '{value}'],
        'b:(.*)' => ['involvedEmployees.user.username', '{value}'],
    ];

    protected $orderKeys = [
        'default' => ['raw' =>'ISNULL(due_on), due_on'],
        'due_on-asc' => ['raw' =>'ISNULL(due_on), due_on'],
        'due_on-desc' => [['due_on', 'desc']],
        'name-asc' => ['name'],
        'name-desc' => [['name', 'desc']],
        'status-asc' => ['raw' => 'field(status, "new", "in progress", "finished"), ISNULL(due_on), due_on'],
        'status-desc' => ['raw' => 'field(status, "finished", "in progress", "new"), ISNULL(due_on), due_on'],
        'priority-asc' => ['raw' => 'field(priority, "low", "medium", "high")'],
        'priority-desc' => ['raw' => 'field(priority, "high", "medium", "low")'],
    ];

    protected $permissionFilters = [
        'tasks.view.responsible' => [['private', false], ['responsibleEmployee.person_id', '{user}']],
        'tasks.view.involved' => [['private', false], ['involvedEmployees.person_id', '{user}']],
        'tasks.view.other' => [
            ['private', false],
            ['!responsibleEmployee.person_id', '{user}'],
            ['!involvedEmployees.person_id', '{user}'],
        ],
        'tasks.view.private.responsible' => [['private', true], ['responsibleEmployee.person_id', '{user}']],
        'tasks.view.private.involved' => [['private', true], ['involvedEmployees.person_id', '{user}']],
        'tasks.view.private.other' => [
            ['private', true],
            ['!responsibleEmployee.person_id', '{user}'],
            ['!involvedEmployees.person_id', '{user}'],
        ],
    ];

    protected static $recordEvents = ['updated'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->filterKeys['ist:bald_fällig'] = [
            'raw' => [
                'due_on between curdate() and date_add(curdate(), interval '.
                ApplicationSettings::get()->task_due_soon_days.' day)',
                'due_on not between curdate() and date_add(curdate(), interval '.
                ApplicationSettings::get()->task_due_soon_days.' day)',
            ],
        ];
    }

    public static function defaultFilter() : ?string
    {
        return Auth::user()->settings->show_finished_items ? null : '!ist:erledigt';
    }

    public static function filterGlobalSearch(string $query, ?int $latestQuantity = null) : Collection
    {
        return Task::filterPermissions()
            ->filterSearch($query)
            ->with('project')
            ->when($latestQuantity && $latestQuantity > 0, function ($query) use ($latestQuantity) {
                return $query->latest('updated_at')->limit($latestQuantity);
            })
            ->get()
            ->map(function(Task $task) {
                return new GlobalSearchResult(
                    Task::class,
                    'Aufgabe',
                    $task->id,
                    "$task->name (Projekt {$task->project->name})",
                    route('tasks.show', $task),
                    $task->created_at,
                    $task->updated_at,
                );
            });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->dontLogIfAttributesChangedOnly(['created_at', 'updated_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function responsibleEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'person_id');
    }

    public function involvedEmployees()
    {
        return $this->morphToMany(Employee::class, 'employeeable', null, 'employeeable_id', 'employee_id')->wherePivot('employee_type', 'involved');
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function getBilledStringAttribute()
    {
        switch ($this->billed) {
            case 'yes':
                return 'billed';
            case 'no':
                return 'not billed';
            case 'warranty':
                return 'warranty';
            default:
                return $this->billed;
        }
    }

    public function getVisibilityStringAttribute()
    {
        switch ($this->private) {
            case false:
                return 'public';
            case true:
                return 'private';
            default:
                return $this->private;
        }
    }

    public function isDueSoon()
    {
        $today = Carbon::now();

        return $this->status !== 'finished'
            && $this->due_on
            && $this->due_on->gt($today)
            && $this->due_on->diffInDays($today) < ApplicationSettings::get()->task_due_soon_days;
    }

    public function isOverdue()
    {
        return $this->status !== 'finished' && $this->due_on && $this->due_on->isPast();
    }

    public function isNew()
    {
        return $this->status === 'new';
    }

    public function isInProgress()
    {
        return $this->status === 'in progress';
    }

    public function isFinished()
    {
        return $this->status === 'finished';
    }
}
