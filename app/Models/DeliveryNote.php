<?php

namespace App\Models;

use App\Support\GlobalSearch\FiltersGlobalSearch;
use App\Support\GlobalSearch\GlobalSearchResult;
use App\Traits\FiltersLatestChanges;
use App\Traits\FiltersSearch;
use App\Traits\HasDownloadRequest;
use App\Traits\HasFilterMetadata;
use App\Traits\HasSignatureRequest;
use App\Traits\OrdersResults;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\HasActivity;
use Spatie\MediaLibrary\HasMedia;

class DeliveryNote extends Model implements FiltersGlobalSearch, HasMedia
{
    use HasFactory;
    use FiltersLatestChanges;
    use FiltersSearch;
    use HasSignatureRequest;
    use HasDownloadRequest;
    use HasActivity;
    use HasFilterMetadata;
    use OrdersResults;

    protected function casts(): array
    {
        return [
        'written_on' => 'date',
    ];
    }

    protected $fillable = [
        'status',
        'written_on',
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
        'nummer:(.*)' => ['title', '{value}'],
        'n:(.*)' => ['title', '{value}'],
        'titel:(.*)' => ['title', '{value}'],
        't:(.*)' => ['title', '{value}'],
        'projekt:(.*)' => ['project.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'p:(.*)' => ['project.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'firma:(.*)' => ['project.company.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'f:(.*)' => ['project.company.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'mitabeiter:(.*)' => ['employee.user.username', '{value}'],
        'm:(.*)' => ['employee.user.username', '{value}'],
    ];

    protected $filterKeyLabels = [
        'ist' => 'Ist',
        'ist:neu' => 'Status: neu',
        'ist:unterschrieben' => 'Status: unterschrieben',
        'ist:u' => 'Status: unterschrieben',
        'ist:erledigt' => 'Status: erledigt',
        'nummer:(.*)' => 'Titel',
        'n:(.*)' => 'Titel',
        'titel:(.*)' => 'Titel',
        't:(.*)' => 'Titel',
        'projekt:(.*)' => 'Projekt',
        'p:(.*)' => 'Projekt',
        'firma:(.*)' => 'Firma',
        'f:(.*)' => 'Firma',
        'mitabeiter:(.*)' => 'Mitarbeiter',
        'm:(.*)' => 'Mitarbeiter',
    ];

    protected $orderKeys = [
        'default' => ['written_on'],
        'written_on-asc' => ['written_on'],
        'written_on-desc' => [['written_on', 'desc']],
        'titel-asc' => ['title'],
        'titel-desc' => [['title', 'desc']],
        'status-asc' => ['raw' => 'case status when "new" then 1 when "signed" then 2 when "finished" then 3 end, written_on'],
        'status-desc' => ['raw' => 'case status when "finished" then 1 when "signed" then 2 when "new" then 3 end, written_on'],
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
        return DeliveryNote::filterSearch($query)
            ->with('project')
            ->when($latestQuantity && $latestQuantity > 0, function ($query) use ($latestQuantity) {
                return $query->latest('updated_at')->limit($latestQuantity);
            })
            ->get()
            ->map(function(DeliveryNote $deliveryNote) {
                return new GlobalSearchResult(
                    DeliveryNote::class,
                    'Lieferschein',
                    $deliveryNote->id,
                    "$deliveryNote->title ({$deliveryNote->project->name})",
                    route('delivery-notes.show', $deliveryNote),
                    $deliveryNote->created_at,
                    $deliveryNote->updated_at,
                );
            });
    }

    public static function resolveGlobalSearchResult(int|string $id): ?GlobalSearchResult
    {
        if (Auth::user()->cannot('viewAny', DeliveryNote::class)) {
            return null;
        }

        $deliveryNote = DeliveryNote::with('project')->find($id);

        if (!$deliveryNote) {
            return null;
        }

        return new GlobalSearchResult(
            DeliveryNote::class,
            'Lieferschein',
            $deliveryNote->id,
            "$deliveryNote->title ({$deliveryNote->project->name})",
            route('delivery-notes.show', $deliveryNote),
            $deliveryNote->created_at,
            $deliveryNote->updated_at,
        );
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
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

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'signed' => 'unterschrieben',
            'finished' => 'erledigt',
            default => 'neu',
        };
    }

    public static function mtdNewDeliveryNotes()
    {
        $now = Carbon::now();
        $firstOfMonth = Carbon::today()->firstOfMonth();

        return DeliveryNote::whereStatus('new')
            ->whereBetween('created_at', [$firstOfMonth, $now])
            ->count();
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
