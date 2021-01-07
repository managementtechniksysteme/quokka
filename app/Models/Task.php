<?php

namespace App\Models;

use App\Traits\FiltersResults;
use App\Traits\HasAttachments;
use App\Traits\OrdersResults;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class Task extends Model implements HasMedia
{
    use FiltersResults;
    use HasAttachments;
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
        'ist:überfällig' => ['due_on', 'curdate()', '>', '<='],
        'projekt:(.*)' => ['project.name', '{value}'],
        'p:(.*)' => ['project.name', '{value}'],
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
        return $this->belongsToMany(Employee::class, 'employee_task', 'task_id', 'employee_id');
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
                return 'privat';
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
            && $this->due_on->diffInDays($today) <= ApplicationSettings::get()->task_due_soon_days;
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
