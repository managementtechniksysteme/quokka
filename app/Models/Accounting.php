<?php

namespace App\Models;

use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;

class Accounting extends Model
{
    use OrdersResults;

    protected $table = 'accounting';

    protected $casts = [
        'service_provided_on' => 'date',
        'service_provided_started_at' => 'datetime:H:i',
        'service_provided_ended_at' => 'datetime:H:i',
        'amount' => 'double',
    ];

    protected $fillable = [
        'service_provided_on',
        'service_provided_started_at',
        'service_provided_ended_at',
        'amount',
        'comment',
        'employee_id',
        'project_id',
        'service_id',
    ];

    protected $orderKeys = [
        'default' => ['service_provided_on', 'service_provided_started_at'],
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'person_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
