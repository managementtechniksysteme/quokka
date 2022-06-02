<?php

namespace App\Models;

use App\Support\GlobalSearch\FiltersGlobalSearch;
use App\Support\GlobalSearch\GlobalSearchResult;
use App\Traits\FiltersPermissions;
use App\Traits\FiltersSearch;
use App\Traits\HasAttachmentsAndSignatureRequests;
use App\Traits\HasDownloadRequest;
use App\Traits\OrdersResults;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;

class ServiceReport extends Model implements FiltersGlobalSearch, HasMedia
{
    use FiltersSearch;
    use FiltersPermissions;
    use HasAttachmentsAndSignatureRequests;
    use HasDownloadRequest;
    use OrdersResults;

    protected $casts = [
        'number' => 'int',
    ];

    protected $fillable = [
        'number', 'status', 'comment', 'project_id', 'employee_id',
    ];

    protected $filterFields = [
        'number', 'comment', 'project.name', 'project.company.name',
    ];

    protected $filterKeys = [
        'ist:neu' => ['status', 'new'],
        'ist:unterschrieben' => ['status', 'signed'],
        'ist:u' => ['status', 'signed'],
        'ist:erledigt' => ['status', 'finished'],
        'nummer:(\d)' => ['number', '{value}'],
        'n:(\d)' => ['number', '{value}'],
        'projekt:(.*)' => ['project.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'p:(.*)' => ['project.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'techniker:(.*)' => ['employee.user.username', '{value}'],
        't:(.*)' => ['employee.user.username', '{value}'],
    ];

    protected $orderKeys = [
        'default' => ['number'],
        'number-asc' => ['number'],
        'number-desc' => [['number', 'desc']],
        'status-asc' => ['raw' => 'field(status, "new", "signed", "finished"), number'],
        'status-desc' => ['raw' => 'field(status, "finished", "signed", "new"), number'],
    ];

    protected $permissionFilters = [
        'service-reports.view.own' => ['employee.person_id', '{user}'],
        'service-reports.view.other' => ['!employee.person_id', '{user}'],
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

    public static function filterGlobalSearch(string $query) : Collection
    {
        return ServiceReport::filterPermissions()
            ->filterSearch($query)
            ->with('project')
            ->get()
            ->map(function(ServiceReport $serviceReport) {
                return new GlobalSearchResult(
                    ServiceReport::class,
                    'Servicebericht',
                    $serviceReport->id,
                    "{$serviceReport->project->name} #$serviceReport->number",
                    route('service-reports.show', $serviceReport)
                );
            });
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

    public function services()
    {
        return $this->hasMany(ServiceReportService::class);
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

    public static function newServiceReports()
    {
        return ServiceReport::whereStatus('new')->count();
    }

    public static function mtdSignedServiceReports()
    {
        $now = Carbon::now();
        $firstOfMonth = Carbon::today()->firstOfMonth();

        return ServiceReport::whereStatus('signed')
            ->whereHas('media', function ($signature) use($firstOfMonth, $now) {
                return $signature
                    ->where('collection_name', 'signature')
                    ->whereBetween('created_at', [$firstOfMonth, $now]);
            })
            ->count();
    }

    public static function signedServiceReports()
    {
        return ServiceReport::whereStatus('signed')->count();
    }
}
