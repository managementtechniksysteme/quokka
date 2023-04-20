<?php

namespace App\Models;

use App\Traits\FiltersPermissions;
use App\Traits\FiltersSearch;
use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Logbook extends Model
{
    use FiltersPermissions;
    use FiltersSearch;
    use OrdersResults;

    protected $table = 'logbook';

    protected $casts = [
        'driven_on' => 'date',
        'start_kilometres' => 'int',
        'end_kilometres' => 'int',
        'driven_kilometres' => 'int',
        'litres_refuelled' => 'int',
    ];

    protected $fillable = [
        'driven_on',
        'start_kilometres',
        'end_kilometres',
        'driven_kilometres',
        'litres_refuelled',
        'origin',
        'destination',
        'comment',
        'employee_id',
        'project_id',
        'vehicle_id',
    ];

    protected $orderKeys = [
        'default' => ['driven_on', 'start_kilometres'],
    ];

    protected $permissionFilters = [
        'logbook.view.own' => ['employee.person_id', '{user}'],
        'logbook.view.other' => ['!employee.person_id', '{user}'],
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'person_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function scopeFilterSearch($query, $params = null)
    {
        if (isset($params['start'])) {
            $query = $query->whereDate('driven_on', '>=', $params['start']);
        }

        if (isset($params['end'])) {
            $query = $query->whereDate('driven_on', '<=', $params['end']);
        }

        if (isset($params['vehicle_id'])) {
            $query = $query->where('vehicle_id', $params['vehicle_id']);
        }

        if (isset($params['project_id'])) {
            $query = $query->where('project_id', $params['project_id']);
        }

        if (isset($params['employee_id'])) {
            $query = $query->where('employee_id', $params['employee_id']);
        }

        if (isset($params['only_own'])) {
            $query = $query->where('employee_id', Auth::user()->employee_id);
        }

        return $query;
    }
}
