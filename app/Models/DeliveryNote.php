<?php

namespace App\Models;

use App\Support\GlobalSearch\GlobalSearchResult;
use App\Traits\FiltersLatestChanges;
use App\Traits\FiltersPermissions;
use App\Traits\FiltersSearch;
use App\Traits\HasDownloadRequest;
use App\Traits\HasSignatureRequest;
use App\Traits\OrdersResults;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DeliveryNote extends Model
{
    use FiltersLatestChanges;
    use FiltersSearch;
    use FiltersPermissions;
    use HasSignatureRequest;
    use HasDownloadRequest;
    use LogsActivity;
    use OrdersResults;

    protected $casts = [
        'written_on' => 'date',
    ];

    protected $fillable = [
        'status',
        'title',
        'comment',
        'employee_id',
        'project_id',
    ];

    protected $filterFields = [
        'title',
        'comment',
        'project.name',
        'project.company.name',
    ];

    protected $filterKeys = [
        'ist:neu' => ['status', 'new'],
        'ist:unterschrieben' => ['status', 'signed'],
        'ist:u' => ['status', 'signed'],
        'ist:erledigt' => ['status', 'finished'],
        'projekt:(.*)' => ['project.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'p:(.*)' => ['project.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'firma:(.*)' => ['project.company.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'f:(.*)' => ['project.company.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'mitabeiter:(.*)' => ['employee.user.username', '{value}'],
        'm:(.*)' => ['employee.user.username', '{value}'],
    ];

    protected $orderKeys = [
        'default' => ['written_on'],
        'written_on-asc' => ['written_on'],
        'written_on-desc' => [['written_on', 'desc']],
        'titel-asc' => ['title'],
        'titel-desc' => [['title', 'desc']],
        'status-asc' => ['raw' => 'field(status, "new", "signed", "finished"), number'],
        'status-desc' => ['raw' => 'field(status, "finished", "signed", "new"), number'],
    ];

    protected static $recordEvents = ['updated'];

    public static function defaultFilter() : ?string
    {
        $filter = '';

        if (! Auth::user()->settings->show_signed_reports) {
            $filter .= '!ist:unterschrieben ';
        }
        if (! Auth::user()->settings->show_finished_items) {
            $filter .= '!ist:erledigt ';
        }

        $filter = trim($filter);

        return $filter === '' ? null : $filter;
    }

    public static function filterGlobalSearch(string $query, ?int $latestQuantity = null) : Collection
    {
        return DeliveryNote::filterPermissions()
            ->filterSearch($query)
            ->with('project')
            ->when($latestQuantity && $latestQuantity > 0, function ($query) use ($latestQuantity) {
                return $query->latest('updated_at')->limit($latestQuantity);
            })
            ->get()
            ->map(function(DeliveryNote $deliveryNote) {
                return new GlobalSearchResult(
                    DeliveryNote::class,
                    'Bautagesbericht',
                    $deliveryNote->id,
                    "$deliveryNote->title ({$deliveryNote->project->name})",
                    route('delivery-notes.show', $deliveryNote),
                    $deliveryNote->created_at,
                    $deliveryNote->updated_at,
                );
            });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('signature')->singleFile()->useDisk('local');
        $this->addMediaCollection('document')->singleFile()->useDisk('local');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'person_id');
    }

    public function document()
    {
        return $this->getFirstMedia('document');
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

    public static function newDeliveryNotes()
    {
        return DeliveryNote::whereStatus('new')->count();
    }

    public static function mtdSignedDeliveryNotes()
    {
        $now = Carbon::now();
        $firstOfMonth = Carbon::today()->firstOfMonth();

        return DeliveryNote::whereStatus('signed')
            ->whereHas('media', function ($signature) use($firstOfMonth, $now) {
                return $signature
                    ->where('collection_name', 'signature')
                    ->whereBetween('created_at', [$firstOfMonth, $now]);
            })
            ->count();
    }

    public static function signedDeliveryNotes()
    {
        return DeliveryNote::whereStatus('signed')->count();
    }

    public function addDocument(UploadedFile $document)
    {
        $this->addMedia($document)->toMediaCollection('document');
    }

    public function deleteDocument()
    {
        $document = $this->document();

        if ($document) {
            $document->delete();
        }
    }
}
