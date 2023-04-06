<?php

namespace App\Models;

use App\Support\GlobalSearch\FiltersGlobalSearch;
use App\Support\GlobalSearch\GlobalSearchResult;
use App\Traits\FiltersLatestChanges;
use App\Traits\FiltersSearch;
use App\Traits\OrdersResults;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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
        'material_costs' => 'double',
        'wage_costs' => 'double',
        'billed_financial_costs' => 'double',
        'is_pre_execution' => 'bool',
        'include_in_finances' => 'bool',
    ];

    protected $fillable = [
        'name', 'starts_on', 'ends_on', 'is_pre_execution', 'include_in_finances', 'material_costs', 'wage_costs', 'billed_financial_costs', 'comment', 'company_id',
    ];

    protected $filterFields = [
        'name', 'company.name',
    ];

    protected $filterKeys = [
        'ist:beendet' => ['raw' => ['ends_on < curdate()', 'ends_on >= curdate() or ends_on is null']],
        'ist:vorphase' => ['is_pre_execution', true],
        'ist:vp' => ['is_pre_execution', true],
        'ist:infinanzen' => ['included_in_finances', true],
        'ist:if' => ['included_in_finances', true],
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

    public static function defaultFilter() : ?string
    {
        return Auth::user()->settings->show_finished_items ? null : '!ist:beendet';
    }

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

    public function flowMeterInspectionReports()
    {
        return $this->hasMany(FlowMeterInspectionReport::class);
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

    public function getIsPreExecutionStringAttribute() {
        return $this->is_pre_execution ? 'ja' : 'nein';
    }

    public function getIncludedInFinancesStringAttribute() {
        return $this->include_in_finances ? 'ja' : 'nein';
    }

    public function getCurrentBilledFinancialCostsAttribute() {
        return $this->billed_financial_costs ?? $this->billed_costs ?? 0;
    }

    public function getCostsAttribute() {
        return ($this->material_costs ?? 0) + ($this->wage_costs ?? 0);
    }

    public function getCurrentMaterialCostsAttribute() {
        return $this->getCurrentMaterialCosts($this->starts_on, $this->ends_on);
    }

    private function getCurrentMaterialCosts(?Carbon $start=null, ?Carbon $end=null) {
        return $this->accounting()
            ->whereIn('service_id', MaterialService::pluck('id'))
            ->when($start, fn ($query) => $query->where('service_provided_on', '>=', $start))
            ->when($end, fn ($query) => $query->where('service_provided_on', '<=', $end))
            ->sum('amount');
    }

    public function getCurrentWageCostsAttribute() {
        return $this->getCurrentWageCosts($this->starts_on, $this->ends_on);
    }

    private function getCurrentWageCosts(?Carbon $start=null, ?Carbon $end=null) {
        return $this->accounting()
            ->whereIn('service_id', WageService::pluck('id'))
            ->when($start, fn ($query) => $query->where('service_provided_on', '>=', $start))
            ->when($end, fn ($query) => $query->where('service_provided_on', '<=', $end))
            ->join('services', function ($join) {
                $join->on('accounting.service_id', '=', 'services.id')
                    ->whereNotNull('services.costs');
            })
            ->sum(DB::raw('accounting.amount*services.costs'));
    }

    public function getCurrentKilometresAttribute() {
        return $this->getCurrentKilometres($this->starts_on, $this->ends_on);
    }

    private function getCurrentKilometres(?Carbon $start=null, ?Carbon $end = null) {
        return $this->logbook()
            ->when($start, fn ($query) => $query->where('driven_on', '>=', $start))
            ->when($end, fn ($query) => $query->where('driven_on', '<=', $end))
            ->sum('driven_kilometres');
    }

    public function getCurrentKilometreCostsAttribute() {
        return $this->getCurrentKilometres($this->starts_on, $this->ends_on) * ApplicationSettings::get()->kilometre_costs;
    }

    public function getCurrentCostsAttribute() {
        return $this->getCurrentCosts($this->starts_on, $this->ends_on);
    }

    public function getCurrentCosts(?Carbon $start=null, ?Carbon $end=null) {
        return $this->getCurrentMaterialCosts($start, $end) +
            $this->getCurrentWageCosts($start, $end) +
            $this->getCurrentKilometres($start, $end) * ApplicationSettings::get()->kilometre_costs;
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
        return $this->getBilledCosts($this->starts_on, $this->ends_on);
    }

    public function getBilledCosts(?Carbon $start=null, ?Carbon $end=null) {
        if(! $this->interimInvoices()->exists()) {
            return null;
        }

        return $this->interimInvoices
            ->when($start, fn ($query) => $query->where('billed_on', '>=', $start))
            ->when($end, fn ($query) => $query->where('billed_on', '<=', $end))
            ->sum('amount');
    }

    public function getCurrentBilledCostsPercentageAttribute() {
        if(! $this->current_billed_financial_costs) {
            return null;
        }

        return ($this->current_costs / $this->current_billed_financial_costs) * 100;
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
