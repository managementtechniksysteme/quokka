<?php

namespace App\Models;

use App\Support\GlobalSearch\FiltersGlobalSearch;
use App\Support\GlobalSearch\GlobalSearchResult;
use App\Traits\FiltersLatestChanges;
use App\Traits\FiltersSearch;
use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Project extends Model implements FiltersGlobalSearch
{
    use FiltersLatestChanges;
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
        'name', 'company.name',
    ];

    protected $filterKeys = [
        'ist:beendet' => ['raw' => ['ends_on < curdate()', 'ends_on >= curdate() or ends_on is null']],
        'firma:(.*)' => ['company.name', '%{value}%', 'LIKE', 'NOT LIKE'],
        'f:(.*)' => ['company.name', '%{value}%', 'LIKE', 'NOT LIKE'],
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

    public static function filterGlobalSearch(string $query, ?int $latestQuantity = null): Collection
    {
        return Project::filterSearch($query)
            ->when($latestQuantity && $latestQuantity > 0, function ($query) use ($latestQuantity) {
                return $query->latest('updated_at')->limit($latestQuantity);
            })
            ->get()
            ->map(function (Project $project) {
                return new GlobalSearchResult(
                    Project::class,
                    'Projekt',
                    $project->id,
                    $project->name,
                    route('projects.show', $project),
                    $project->created_at,
                    $project->updated_at,
                );
            });
    }

    public function interimInvoices()
    {
        return $this->hasMany(InterimInvoice::class);
    }

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

    public function additionsReports()
    {
        return $this->hasMany(AdditionsReport::class);
    }

    public function inspectionReports()
    {
        return $this->hasMany(InspectionReport::class);
    }

    public function constructionReports()
    {
        return $this->hasMany(ConstructionReport::class);
    }

    public function accounting()
    {
        return $this->hasMany(Accounting::class);
    }

    public function logbook()
    {
        return $this->hasMany(Logbook::class);
    }

    public function getShortNameAttribute() {
        return Str::beforeLast($this->name, ' [');
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
                ->whereIn('service_id', WageService::pluck('id'))
                ->join('services', function ($join) {
                    $join->on('accounting.service_id', '=', 'services.id')
                        ->whereNotNull('services.costs');
                })
                ->sum(DB::raw('accounting.amount*services.costs'));
    }

    public function getCurrentCostsAttribute() {
        return $this->current_material_costs + $this->current_wage_costs;
    }

    public function getCurrentMaterialCostsPercentageAttribute() {
        if(! $this->material_costs) {
            return null;
        }

        return ($this->current_material_costs / $this->material_costs) * 100;
    }

    public function getCurrentWageCostsPercentageAttribute() {
        if(! $this->wage_costs) {
            return null;
        }

        return ($this->current_wage_costs / $this->wage_costs) * 100;
    }

    public function getCurrentCostsPercentageAttribute() {
        if(! $this->material_costs && ! $this->wage_costs) {
            return null;
        }

        return ($this->current_costs / $this->costs) * 100;
    }

    public function getBilledCostsAttribute() {
        if(! $this->interimInvoices()->exists()) {
            return null;
        }

        return $this->interimInvoices->sum('amount');
    }

    public function getCurrentBilledCostsPercentageAttribute() {
        if(! $this->billed_costs) {
            return null;
        }

        return ($this->current_costs / $this->billed_costs) * 100;
    }

    public function getCurrentBilledPercentageAttribute() {
        if(! $this->billed_costs) {
            return null;
        }

        return ($this->current_costs / $this->billed_costs) * 100;
    }

    public function getCurrentMaterialCostsStatusAttribute() {
        $warningPercentage = ApplicationSettings::get()->project_material_costs_warning_percentage;
        if(! ($this->current_material_costs_percentage && $warningPercentage)) {
            return null;
        }

        if($this->current_material_costs_percentage < $warningPercentage) {
            return 'success';
        }
        elseif($this->current_material_costs_percentage < 100) {
            return 'warning';
        }
        else {
            return 'danger';
        }
    }

    public function getCurrentWageCostsStatusAttribute() {
        $warningPercentage = ApplicationSettings::get()->project_wage_costs_warning_percentage;
        if(! ($this->current_wage_costs_percentage && $warningPercentage)) {
            return null;
        }

        if($this->current_wage_costs_percentage < $warningPercentage) {
            return 'success';
        }
        elseif($this->current_wage_costs_percentage < 100) {
            return 'warning';
        }
        else {
            return 'danger';
        }
    }

    public function getCurrentCostsStatusAttribute() {
        $warningPercentage = ApplicationSettings::get()->project_overall_costs_warning_percentage;
        if(! ($this->current_costs_percentage && $warningPercentage)) {
            return null;
        }

        if($this->current_costs_percentage < $warningPercentage) {
            return 'success';
        }
        elseif($this->current_costs_percentage < 100) {
            return 'warning';
        }
        else {
            return 'danger';
        }
    }

    public function getCurrentBilledCostsStatusAttribute() {
        $warningPercentage = ApplicationSettings::get()->project_billed_costs_warning_percentage;
        if(! ($this->current_billed_costs_percentage && $warningPercentage)) {
            return null;
        }

        if($this->current_billed_costs_percentage < $warningPercentage) {
            return 'success';
        }
        elseif($this->current_billed_costs_percentage < 100) {
            return 'warning';
        }
        else {
            return 'danger';
        }
    }

    public function getCurrentKilometresAttribute() {
        return $this->logbook()->sum('driven_kilometres');
    }

    public function getReport($params)
    {
        $currencyUnit = ApplicationSettings::get()->currency_unit;

        $report = $this->accounting()
            ->selectRaw('accounting.service_provided_on as service_provided_on, accounting.service_id as service_id, concat(services.name, " (", ifnull(services.unit, "'.$currencyUnit.'"), ")")  as service, accounting.employee_id as employee_id, users.username as username, SUM(amount) as amount, group_concat(distinct accounting.comment separator ", ") as comment')
            ->join('users', 'accounting.employee_id', '=', 'users.employee_id')
            ->join('projects', 'accounting.project_id', '=', 'projects.id')
            ->join('services', 'accounting.service_id', '=', 'services.id')
            ->groupBy('accounting.service_provided_on')
            ->groupBy('accounting.employee_id')
            ->groupBy('users.username')
            ->groupBy('accounting.service_id')
            ->groupBy('services.name')
            ->groupBy('services.unit')
            ->orderBy('accounting.service_provided_on')
            ->orderBy('users.username')
            ->orderBy('services.name');

        if (isset($params['start'])) {
            $report = $report->where('accounting.service_provided_on', '>=', $params['start']);
        }

        if (isset($params['end'])) {
            $report = $report->where('accounting.service_provided_on', '<=', $params['end']);
        }

        if (isset($params['employee_ids'])) {
            $report = $report->whereIn('accounting.employee_id', $params['employee_ids']);
        }

        if (isset($params['service_ids'])) {
            $report = $report->whereIn('accounting.service_id', $params['service_ids']);
        }

        return $report->get();
    }

    public function getReportSums($params)
    {
        $currencyUnit = ApplicationSettings::get()->currency_unit;

        $sums = $this->accounting()
            ->selectRaw('concat(services.name, " (", ifnull(services.unit, "'.$currencyUnit.'"), ")")  as service, SUM(amount) as amount')
            ->join('services', 'accounting.service_id', '=', 'services.id')
            ->groupBy('services.name')
            ->groupBy('services.unit')
            ->orderByDesc('services.type')
            ->orderBy('services.name');

        if (isset($params['start'])) {
            $sums = $sums->where('accounting.service_provided_on', '>=', $params['start']);
        }

        if (isset($params['end'])) {
            $sums = $sums->where('accounting.service_provided_on', '<=', $params['end']);
        }

        if (isset($params['employee_ids'])) {
            $sums = $sums->whereIn('accounting.employee_id', $params['employee_ids']);
        }

        if (isset($params['service_ids'])) {
            $sums = $sums->whereIn('accounting.service_id', $params['service_ids']);
        }

        return $sums->get();
    }
}
