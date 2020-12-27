<?php

namespace App\Models;

use App\Traits\FiltersResults;
use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    use FiltersResults;
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

    protected $filterFields = [
        'title',
    ];

    protected $filterKeys = [
        'hat:folgetermin' => ['raw' => ['next_meeting_on >= curdate()', 'next_meeting_on < curdate() or next_meeting_on is null']],
        'nummer:(\d)' => ['number', '{value}'],
        'n:(\d)' => ['number', '{value}'],
        'projekt:(.*)' => ['project.name', '{value}'],
        'p:(.*)' => ['project.name', '{value}'],
        'von:(.*)' => ['employeeComposer.user.username', '{value}'],
        'an:(.*)' => ['hasraw' => ['personRecipient', 'concat(first_name, " ", last_name) like "%{value}%"', 'concat(first_name, " ", last_name) not like "%{value}%"']],
        'beteiligt:(.*)' => ['hasraw' => ['presentPeople', 'concat(first_name, " ", last_name) like "%{value}%"', 'concat(first_name, " ", last_name) not like "%{value}%"']],
        'b:(.*)' => ['hasraw' => ['presentPeople', 'concat(first_name, " ", last_name) like "%{value}%"', 'concat(first_name, " ", last_name) not like "%{value}%"']],
        'beteiligt_mitarbeiter:(.*)' => ['presentPeople.employee.user.username', '{value}'],
        'bm:(.*)' => ['presentPeople.employee.user.username', '{value}'],
        'verständigt:(.*)' => ['hasraw' => ['notifiedPeople', 'concat(first_name, " ", last_name) like "%{value}%"', 'concat(first_name, " ", last_name) not like "%{value}%"']],
        'v:(.*)' => ['hasraw' => ['notifiedPeople', 'concat(first_name, " ", last_name) like "%{value}%"', 'concat(first_name, " ", last_name) not like "%{value}%"']],
        'verständigt_mitarbeiter:(.*)' => ['notifiedPeople.employee.user.username', '{value}'],
        'vm:(.*)' => ['notifiedPeople.employee.user.username', '{value}'],
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
