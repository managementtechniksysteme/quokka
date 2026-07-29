<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Project;
use App\Support\Metrics\MetricsCalculator;
use App\Support\Metrics\MetricsFilters;
use Illuminate\Http\Request;

class MetricsController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:tools-viewmetrics');
    }

    public function index(Request $request)
    {
        $filters = MetricsFilters::fromRequest($request);
        $metrics = new MetricsCalculator($filters);

        $companies = Company::order()->get(['id', 'name', 'name_2']);
        $employees = Employee::with('person')->get()->sortBy('person.name')->values();
        $projects = Project::order()->get(['id', 'name']);
        $reportTypes = MetricsCalculator::reportTypeOptions();

        return view('metrics.index', [
            'filters' => $filters,
            'filtersArray' => $filters->toArray(),
            'metrics' => $metrics,
            'companies' => $companies,
            'employees' => $employees,
            'projects' => $projects,
            'reportTypes' => $reportTypes,
        ]);
    }
}
