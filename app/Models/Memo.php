<?php

namespace App\Models;

use App\Support\GlobalSearch\FiltersGlobalSearch;
use App\Support\GlobalSearch\GlobalSearchResult;
use App\Traits\FiltersLatestChanges;
use App\Traits\FiltersPermissions;
use App\Traits\FiltersSearch;
use App\Traits\HasAttachments;
use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;

class Memo extends Model implements FiltersGlobalSearch, HasMedia
{
    use FiltersLatestChanges;
    use HasAttachments;
    use FiltersPermissions;
    use FiltersSearch;
    use OrdersResults;

    protected $casts = [
        'number' => 'int',
        'draft' => 'bool',
        'meeting_held_on' => 'date',
        'next_meeting_on' => 'date',
    ];

    protected $fillable = [
        'number', 'draft', 'title', 'meeting_held_on', 'next_meeting_on', 'comment', 'project_id',
        'employee_id', 'person_id',
    ];

    protected $filterFields = [
        'title',
        'project.name',
        'project.company.name',
        'employeeComposer.person.first_name',
        'employeeComposer.person.last_name',
        'employeeComposer.user.username',
        'personRecipient.first_name',
        'personRecipient.last_name',
        'personRecipient.employee.user.username',
        'presentPeople.first_name',
        'presentPeople.last_name',
        'presentPeople.employee.user.username',
        'notifiedPeople.first_name',
        'notifiedPeople.last_name',
        'notifiedPeople.employee.user.username',
    ];

    protected $filterKeys = [
        'hat:folgetermin' => ['raw' => ['next_meeting_on >= curdate()', 'next_meeting_on < curdate() or next_meeting_on is null']],
        'nummer:(\d)' => ['number', '{value}'],
        'n:(\d)' => ['number', '{value}'],
        'ist:entwurf' => ['draft', true],
        'ist:e' => ['draft', true],
        'projekt:(.*)' => ['project.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'p:(.*)' => ['project.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'firma:(.*)' => ['project.company.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'f:(.*)' => ['project.company.name', '%{value}%', 'LIKE', 'NOT LIKE'],
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
        'draft-asc' => ['draft'],
        'draft-desc' => [['draft', 'desc']],
    ];

    protected $permissionFilters = [
        'memos.view.sender' => ['employeeComposer.person_id', '{user}'],
        'memos.view.recipient' => ['personRecipient.id', '{user}'],
        'memos.view.present' => ['presentPeople.id', '{user}'],
        'memos.view.notified' => ['notifiedPeople.id', '{user}'],
        'memos.view.other' => [
            ['!employeeComposer.person_id', '{user}'],
            ['!personRecipient.id', '{user}'],
            ['!presentPeople.id', '{user}'],
            ['!notifiedPeople.id', '{user}'],
        ],
    ];

    public static function filterGlobalSearch(string $query, ?int $latestQuantity = null) : Collection
    {
        return Memo::filterPermissions()
            ->filterSearch($query)
            ->with('project')
            ->when($latestQuantity && $latestQuantity > 0, function ($query) use ($latestQuantity) {
                return $query->latest('updated_at')->limit($latestQuantity);
            })
            ->get()
            ->map(function(Memo $memo) {
                return new GlobalSearchResult(
                    Memo::class,
                    'Aktenvermerk',
                    $memo->id,
                    "$memo->title (Projekt {$memo->project->name})",
                    route('memos.show', $memo),
                    $memo->created_at,
                    $memo->updated_at,
                );
            });
    }

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

    public function getDraftStringAttribute()
    {
        switch ($this->draft) {
            case false:
                return 'no';
            case true:
                return 'yes';
            default:
                return $this->draft;
        }
    }
}
