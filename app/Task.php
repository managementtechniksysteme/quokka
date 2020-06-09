<?php

namespace App;

use App\Traits\OrdersResults;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
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

    protected $orderKeys = [
        'default' => ['raw' =>'ISNULL(due_on), due_on'],
        'due_on-asc' => ['raw' =>'ISNULL(due_on), due_on'],
        'due_on-desc' => [['due_on', 'desc']],
        'name-asc' => ['name'],
        'name-desc' => [['name', 'desc']],
        'status-asc' => ['raw' => 'field(status, "new", "in progress", "finished"), ISNULL(due_on), due_on'],
        'status-desc' => ['raw' => 'field(status, "finished", "in progress", "new"), ISNULL(due_on), due_on'],
    ];

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
            && $this->due_on->diffInDays($today) <= 7;
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
