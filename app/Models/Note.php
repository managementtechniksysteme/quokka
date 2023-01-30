<?php

namespace App\Models;

use App\Support\GlobalSearch\FiltersGlobalSearch;
use App\Support\GlobalSearch\GlobalSearchResult;
use App\Traits\FiltersLatestChanges;
use App\Traits\FiltersSearch;
use App\Traits\HasAttachments;
use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;

class Note extends Model implements FiltersGlobalSearch, HasMedia
{
    use FiltersLatestChanges;
    use HasAttachments;
    use FiltersSearch;
    use OrdersResults;

    protected $fillable = [
        'title', 'comment',
    ];

    protected $filterFields = [
        'title',
        'comment'
    ];

    protected $filterKeys = [];

    protected $orderKeys = [
        'default' => [['created_at', 'desc']],
        'created_at-asc' => ['created_at'],
        'created_at-desc' => [['created_at', 'desc']],
        'title-asc' => ['title'],
        'title-desc' => [['title', 'desc']],
    ];

    public static function filterGlobalSearch(string $query, ?int $latestQuantity = null) : Collection
    {
        return \Auth::user()->employee->notes()
            ->filterSearch($query)
            ->when($latestQuantity && $latestQuantity > 0, function ($query) use ($latestQuantity) {
                return $query->latest('updated_at')->limit($latestQuantity);
            })
            ->get()
            ->map(function(Note $note) {
                $name = $note->title_string ? "$note->title_string ($note->truncated_comment)" : "$note->truncated_comment";
                return new GlobalSearchResult(
                    Note::class,
                    'Notiz',
                    $note->id,
                    $name,
                    route('notes.show', $note),
                    $note->created_at,
                    $note->updated_at,
                );
            });
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'person_id');
    }

    public function getTitleStringAttribute()
    {
        return $this->title ?? '';
    }

    public function getTruncatedCommentAttribute()
    {
        return Str::words($this->comment, 10);
    }
}
