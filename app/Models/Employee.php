<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $casts = [
        'person_id' => 'int',
        'entered_on' => 'date',
        'left_on' => 'date',
        'holidays' => 'int',
    ];

    protected $fillable = [
        'person_id', 'entered_on', 'left_on', 'holidays',
    ];

    protected $primaryKey = 'person_id';
    public $incrementing = false;

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'employee_id');
    }

    public function tasksResponsibleFor()
    {
        return $this->hasMany(Task::class, 'employee_id');
    }

    public function tasksInvolvedIn()
    {
        return $this->belongsToMany(Task::class, 'employee_task', 'employee_id', 'task_id');
    }

    public function composedMemos()
    {
        return $this->hasMany(Memo::class);
    }

    public function taskComments()
    {
        return $this->hasMany(TaskComment::class, 'employee_id');
    }
}
