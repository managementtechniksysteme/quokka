<?php

namespace App\Models;

use App\Support\GlobalSearch\FiltersGlobalSearch;
use App\Support\GlobalSearch\GlobalSearchResult;
use App\Traits\FiltersLatestChanges;
use App\Traits\FiltersPermissions;
use App\Traits\FiltersSearch;
use App\Traits\HasAttachments;
use App\Traits\HasFilterMetadata;
use App\Traits\OrdersResults;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\HasActivity;
use Spatie\MediaLibrary\HasMedia;

class Task extends Model implements FiltersGlobalSearch, HasMedia
{
    use HasFactory;
    use FiltersLatestChanges;
    use FiltersSearch;
    use FiltersPermissions;
    use HasAttachments;
    use HasActivity;
    use HasFilterMetadata;
    use OrdersResults;

    protected function casts(): array
    {
        return [
        'starts_on' => 'date',
        'ends_on' => 'date',
        'due_on' => 'date',
        'private' => 'boolean',
    ];
    }

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
        'ist:überfällig' => ['raw' => ['due_on < CURRENT_DATE and status != "finished"', 'due_on <= CURRENT_DATE or (due_on > CURRENT_DATE and status = "finished")']],
        'projekt:(.*)' => ['project.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'p:(.*)' => ['project.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'firma:(.*)' => ['project.company.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'f:(.*)' => ['project.company.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'verantwortlich:(.*)' => ['responsibleEmployee.user.username', '{value}'],
        'v:(.*)' => ['responsibleEmployee.user.username', '{value}'],
        'beteiligt:(.*)' => ['involvedEmployees.user.username', '{value}'],
        'b:(.*)' => ['involvedEmployees.user.username', '{value}'],
    ];

    protected $filterKeyLabels = [
        'ist' => 'Ist',
        'ist:privat' => 'Privat',
        'ist:niedrig' => 'Priorität: niedrig',
        'ist:mittel' => 'Priorität: mittel',
        'ist:hoch' => 'Priorität: hoch',
        'ist:neu' => 'Status: neu',
        'ist:in_bearbeitung' => 'Status: in Bearbeitung',
        'ist:ib' => 'Status: in Bearbeitung',
        'ist:erledigt' => 'Status: erledigt',
        'ist:verrechnet' => 'Verrechnet',
        'ist:nicht_verrechnet' => 'Nicht verrechnet',
        'ist:nv' => 'Nicht verrechnet',
        'ist:garantie' => 'Garantie',
        'ist:überfällig' => 'Überfällig',
        'ist:bald_fällig' => 'Bald fällig',
        'projekt:(.*)' => 'Projekt',
        'p:(.*)' => 'Projekt',
        'firma:(.*)' => 'Firma',
        'f:(.*)' => 'Firma',
        'verantwortlich:(.*)' => 'Verantwortlich',
        'v:(.*)' => 'Verantwortlich',
        'beteiligt:(.*)' => 'Beteiligt',
        'b:(.*)' => 'Beteiligt',
    ];

    protected $orderKeys = [
        'default' => ['raw' =>'due_on is null, due_on'],
        'due_on-asc' => ['raw' =>'due_on is null, due_on'],
        'due_on-desc' => [['due_on', 'desc']],
        'name-asc' => ['name'],
        'name-desc' => [['name', 'desc']],
        'status-asc' => ['raw' => 'case status when "new" then 1 when "in progress" then 2 when "finished" then 3 end, due_on is null, due_on'],
        'status-desc' => ['raw' => 'case status when "finished" then 1 when "in progress" then 2 when "new" then 3 end, due_on is null, due_on'],
        'priority-asc' => ['raw' => 'case priority when "low" then 1 when "medium" then 2 when "high" then 3 end'],
        'priority-desc' => ['raw' => 'case priority when "high" then 1 when "medium" then 2 when "low" then 3 end'],
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

        // The day count is a plain PHP value already known before the query runs, so the
        // boundary date is computed here rather than via MySQL-only SQL date arithmetic
        // (date_add(curdate(), interval ... day)), which has no portable equivalent.
        $dueSoonOn = Carbon::today()->addDays(ApplicationSettings::get()->task_due_soon_days)->toDateString();

        $this->filterKeys['ist:bald_fällig'] = [
            'raw' => [
                "due_on between CURRENT_DATE and '$dueSoonOn'",
                "due_on not between CURRENT_DATE and '$dueSoonOn'",
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

    public static function resolveGlobalSearchResult(int|string $id): ?GlobalSearchResult
    {
        $task = Task::filterPermissions()
            ->with('project')
            ->find($id);

        if (!$task) {
            return null;
        }

        return new GlobalSearchResult(
            Task::class,
            'Aufgabe',
            $task->id,
            "$task->name (Projekt {$task->project->name})",
            route('tasks.show', $task),
            $task->created_at,
            $task->updated_at,
        );
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->dontLogIfAttributesChangedOnly(['created_at', 'updated_at'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
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

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'in progress' => 'in Arbeit',
            'finished' => 'erledigt',
            default => 'neu',
        };
    }
}
