<?php

namespace App;

use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    use OrdersResults;

    protected $fillable = [
        'comment', 'task_id', 'employee_id'
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
