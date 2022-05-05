<?php

namespace App\Models;

use App\Traits\FiltersSearch;
use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use FiltersSearch;
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

    public function logbook()
    {
        return $this->hasMany(Logbook::class);
    }

    public function getCostsAttribute() {
        return ($this->material_costs ?? 0) + ($this->wage_costs ?? 0);
    }

    public function getCurrentMaterialCostsAttribute() {
        return $this->accounting()
            ->whereIn('service_id', MaterialService::pluck('id'))
            ->sum('amount');
    }

    public function getCurrentWageCostsAttribute() {
        return $this->accounting()
            ->selectRaw('accounting.amount*services.costs as costs')
            ->whereIn('service_id', WageService::pluck('id'))
            ->join('services', function ($join) {
                $join->on('accounting.service_id', '=', 'services.id')
                    ->whereNotNull('services.costs');
            })
            ->sum('costs');
    }

    public function getCurrentCostsAttribute() {
        return $this->current_material_costs + $this->current_wage_costs;
    }

    public function getCurrentMaterialCostsPercentageAttribute() {
        if(!$this->material_costs) {
            return null;
        }

        return ($this->current_material_costs / $this->material_costs) * 100;
    }

    public function getCurrentWageCostsPercentageAttribute() {
        if(!$this->wage_costs) {
            return null;
        }

        return ($this->current_wage_costs / $this->wage_costs) * 100;
    }

    public function getCurrentCostsPercentageAttribute() {
        if(!$this->material_costs && !$this->wage_costs) {
            return null;
        }

        return ($this->current_costs / $this->costs) * 100;
    }

    public function getCurrentMaterialCostsStatusAttribute() {
        $warningPercentage = ApplicationSettings::get()->project_material_costs_warning_percentage;
        if(!($this->current_material_costs_percentage && $warningPercentage)) {
            return null;
        }

        if($this->current_material_costs_percentage < $warningPercentage) {
            return 'success';
        }
        elseif($this->current_material_costs_percentage < 100 ) {
            return 'warning';
        }
        else {
            return 'danger';
        }
    }

    public function getCurrentWageCostsStatusAttribute() {
        $warningPercentage = ApplicationSettings::get()->project_wage_costs_warning_percentage;
        if(!($this->current_wage_costs_percentage && $warningPercentage)) {
            return null;
        }

        if($this->current_wage_costs_percentage < $warningPercentage) {
            return 'success';
        }
        elseif($this->current_wage_costs_percentage < 100 ) {
            return 'warning';
        }
        else {
            return 'danger';
        }
    }

    public function getCurrentCostsStatusAttribute() {
        $warningPercentage = ApplicationSettings::get()->project_overall_costs_warning_percentage;
        if(!($this->current_costs_percentage && $warningPercentage)) {
            return null;
        }

        if($this->current_costs_percentage < $warningPercentage) {
            return 'success';
        }
        elseif($this->current_costs_percentage < 100 ) {
            return 'warning';
        }
        else {
            return 'danger';
        }
    }

    public function getCurrentKilometresAttribute() {
        return $this->logbook()->sum('driven_kilometres');
    }
}
