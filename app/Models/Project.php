<?php

namespace App\Models;

use App\Traits\FiltersResults;
use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use FiltersResults;
    use OrdersResults;

    protected $casts = [
        'starts_on' => 'date',
        'ends_on' => 'date',
    ];

    protected $fillable = [
        'name', 'starts_on', 'ends_on', 'material_costs', 'wage_costs', 'comment', 'company_id',
    ];

    protected $filterFields = [
        'name',
    ];

    protected $filterKeys = [
        'ist:beendet' => ['raw' => ['ends_on < curdate()', 'ends_on >= curdate() or ends_on is null']],
        'firma:(.*)' => ['company.name', '{value}'],
        'f:(.*)' => ['company.name', '{value}'],
    ];

    protected $orderKeys = [
        'default' => ['name'],
        'name-asc' => ['name'],
        'name-desc' => [['name', 'desc']],
        'material-costs-asc' => ['material_costs'],
        'material-costs-desc' => [['material_costs', 'desc']],
        'wage-costs-asc' => ['wage_costs'],
        'wage-costs-desc' => [['wage_costs', 'desc']],
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function memos()
    {
        return $this->hasMany(Memo::class);
    }

    public function serviceReports()
    {
        return $this->hasMany(ServiceReport::class);
    }

    public function accounting()
    {
        return $this->hasMany(Accounting::class);
    }
}
