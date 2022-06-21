<?php

namespace App\Models;

use App\Support\GlobalSearch\GlobalSearchResult;
use App\Traits\FiltersLatestChanges;
use App\Traits\FiltersSearch;
use App\Traits\HasAttachments;
use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;

class TaskComment extends Model implements HasMedia
{
    use FiltersLatestChanges;
    use FiltersSearch;
    use HasAttachments;
    use OrdersResults;

    protected $fillable = [
        'comment', 'task_id', 'employee_id',
    ];

    protected $filterFields = [];

    protected $orderKeys = [
        'default' => ['created_at'],
        'created_at-asc' => ['created_at'],
        'created_at-desc' => [['created_at', 'desc']],
    ];

    public static function filterGlobalSearch(string $query, ?int $latestQuantity = null) : Collection
    {
        return TaskComment::filterSearch($query)
            ->with('task.project')
            ->with('employee.person')
            ->when($latestQuantity && $latestQuantity > 0, function ($query) use ($latestQuantity) {
                return $query->latest('updated_at')->limit($latestQuantity);
            })
            ->get()
            ->filter(function ($taskComment) {
                return Auth::user()->can('view', $taskComment);
            })
            ->map(function(TaskComment $taskComment) {
                return new GlobalSearchResult(
                    TaskComment::class,
                    'Kommentar',
                    $taskComment->id,
                    "{$taskComment->employee->person->name} in Aufgabe {$taskComment->task->name} (Projekt {$taskComment->task->project->name})",
                    route('tasks.show', $taskComment->task),
                    $taskComment->created_at,
                    $taskComment->updated_at,
                );
            });
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'person_id');
    }
}
