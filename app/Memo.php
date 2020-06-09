<?php

namespace App;

use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    use OrdersResults;

    protected $casts = [
        'number' => 'int',
        'meeting_held_on' => 'date',
        'next_meeting_on' => 'date',
    ];

    protected $fillable = [
        'number', 'title', 'meeting_held_on', 'next_meeting_on', 'comment', 'project_id',
        'employee_id', 'person_id',
    ];

    protected $orderKeys = [
        'default' => ['number'],
        'number-asc' => ['number'],
        'number-desc' => [['number', 'desc']],
        'meeting_held_on-asc' => ['meeting_held_on'],
        'meeting_held_on-desc' => [['meeting_held_on', 'desc']],
        'title-asc' => ['title'],
        'title-desc' => [['title', 'desc']],
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function employeeComposer()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'person_id');
    }

    public function personRecipient()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function presentPeople()
    {
        return $this->morphToMany(Person::class, 'personable')->wherePivot('person_type', 'present');
    }

    public function notifiedPeople()
    {
        return $this->morphToMany(Person::class, 'personable')->wherePivot('person_type', 'notified');
    }
}
