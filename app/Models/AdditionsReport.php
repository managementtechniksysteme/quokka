<?php

namespace App\Models;

use App\Traits\FiltersPermissions;
use App\Traits\FiltersSearch;
use App\Traits\HasAttachmentsAndSignatureRequests;
use App\Traits\HasDownloadRequest;
use App\Traits\OrdersResults;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;

class AdditionsReport extends Model implements HasMedia
{
    use FiltersSearch;
    use FiltersPermissions;
    use HasAttachmentsAndSignatureRequests;
    use HasDownloadRequest;
    use OrdersResults;

    protected $casts = [
        'number' => 'int',
        'services_provided_on' => 'date',
        'hours' => 'double',
        'minimum_temperature' => 'int',
        'maximum_temperature' => 'int',
    ];

    protected $fillable = [
        'number',
        'status',
        'services_provided_on',
        'hours',
        'other_visitors',
        'inspection_comment',
        'missing_documents',
        'special_occurrences',
        'imminent_danger',
        'concerns',
        'weather',
        'minimum_temperature',
        'maximum_temperature',
        'comment',
        'project_id',
        'employee_id',
    ];

    protected $filterFields = [
        'number',
        'other_visitors',
        'inspection_comment',
        'missing_documents',
        'special_occurrences',
        'imminent_danger',
        'concerns',
        'comment',
    ];

    protected $filterKeys = [
        'ist:neu' => ['status', 'new'],
        'ist:unterschrieben' => ['status', 'signed'],
        'ist:u' => ['status', 'signed'],
        'ist:erledigt' => ['status', 'finished'],
        'nummer:(\d)' => ['number', '{value}'],
        'n:(\d)' => ['number', '{value}'],
        'projekt:(.*)' => ['project.name', '{value}'],
        'p:(.*)' => ['project.name', '{value}'],
        'techniker:(.*)' => ['employee.user.username', '{value}'],
        't:(.*)' => ['employee.user.username', '{value}'],
        'beteiligt:(.*)' => ['hasraw' => ['presentPeople', 'concat(first_name, " ", last_name) like "%{value}%"', 'concat(first_name, " ", last_name) not like "%{value}%"']],
        'b:(.*)' => ['hasraw' => ['presentPeople', 'concat(first_name, " ", last_name) like "%{value}%"', 'concat(first_name, " ", last_name) not like "%{value}%"']],
        'beteiligt_mitarbeiter:(.*)' => ['involvedEmployees.user.username', '{value}'],
        'bm:(.*)' => ['involvedEmployees.user.username', '{value}'],
    ];

    protected $orderKeys = [
        'default' => ['number'],
        'services_provided_on-asc' => ['services_provided_on'],
        'services_provided_on-desc' => [['services_provided_on', 'desc']],
        'number-asc' => ['number'],
        'number-desc' => [['number', 'desc']],
        'status-asc' => ['raw' => 'field(status, "new", "signed", "finished"), number'],
        'status-desc' => ['raw' => 'field(status, "finished", "signed", "new"), number'],
    ];

    protected $permissionFilters = [
        'additions-reports.view.own' => ['employee.person_id', '{user}'],
        'additions-reports.view.involved' => ['involvedEmployees.person_id', '{user}'],
        'additions-reports.view.other' => [
            ['!employee.person_id', '{user}'],
            ['!involvedEmployees.person_id', '{user}']
        ],
    ];

    public static function defaultFilter() : ?string
    {
        $filter = '';

        if (Auth::user()->settings->show_only_own_reports) {
            $filter .= 't:' . Auth::user()->username . ' ';
        }
        if (! Auth::user()->settings->show_finished_items) {
            $filter .= '!ist:erledigt ';
        }

        $filter = trim($filter);

        return $filter === '' ? null : $filter;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('signature')->singleFile()->useDisk('local');
        $this->addMediaCollection('attachments')->useDisk('local');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'person_id');
    }

    public function involvedEmployees()
    {
        return $this->morphToMany(Employee::class, 'employeeable', null, 'employeeable_id', 'employee_id')->wherePivot('employee_type', 'involved');
    }

    public function presentPeople()
    {
        return $this->morphToMany(Person::class, 'personable')->wherePivot('person_type', 'present');
    }

    public function isNew()
    {
        return $this->status === 'new';
    }

    public function isSigned()
    {
        return $this->status === 'signed';
    }

    public function isFinished()
    {
        return $this->status === 'finished';
    }

    public static function newAdditionsReports()
    {
        return AdditionsReport::whereStatus('new')->count();
    }

    public static function mtdSignedAdditionsReports()
    {
        $today = Carbon::today();
        $firstOfMonth = Carbon::today()->firstOfMonth();

        return AdditionsReport::whereStatus('signed')
            ->whereHas('media', function ($signature) use($firstOfMonth, $today) {
                return $signature->whereBetween('created_at', [$firstOfMonth, $today]);
            })
            ->count();
    }

    public static function signedAdditionsReports()
    {
        return AdditionsReport::whereStatus('signed')->count();
    }
}
