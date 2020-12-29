<?php

namespace App\Models;

use App\Traits\HasAttachments;
use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class TaskComment extends Model implements HasMedia
{
    use HasAttachments;
    use OrdersResults;

    protected $fillable = [
        'comment', 'task_id', 'employee_id',
    ];

    protected $orderKeys = [
        'default' => ['created_at'],
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'person_id');
    }
}
