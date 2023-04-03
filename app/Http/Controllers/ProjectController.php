<?php

namespace App\Http\Controllers;

use App\Helpers\Finances;
use App\Http\Requests\ProjectCreateRequest;
use App\Http\Requests\ProjectDownloadListRequest;
use App\Http\Requests\ProjectDownloadRequest;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Models\AdditionsReport;
use App\Models\ApplicationSettings;
use App\Models\Company;
use App\Models\ConstructionReport;
use App\Models\FinanceRecord;
use App\Models\FlowMeterInspectionReport;
use App\Models\InspectionReport;
use App\Models\InterimInvoice;
use App\Models\Memo;
use App\Models\Person;
use App\Models\Project;
use App\Models\Service;
use App\Models\ServiceReport;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ZsgsDesign\PDFConverter\Latex;

class ProjectController extends Controller
{
    protected function resourceAbilityMap()
    {
        return array_merge(parent::resourceAbilityMap(), [
            'showEmail' => 'email',
            'email' => 'email',
            'download' => 'createPdf',
        ]);
    }

    public function resourceMethodsWithoutModels()
    {
        return array_merge(parent::resourceMethodsWithoutModels(), ['downloadList']);
    }

    public function __construct()
    {
        $this->authorizeResource(Project::class, 'project');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Project::handleDefaultFilter($request);

        $projects = Project::filterSearch($request->search)
            ->order($request->sort)
            ->with('company')
            ->withCount('tasks')
            ->withCount('memos')
            ->withCount('serviceReports')
            ->paginate(Auth::user()->settings->list_pagination_size)
            ->appends($request->except('page'));

        $projectOverallCostsWarningPercentage = ApplicationSettings::get()->project_overall_costs_warning_percentage;
        $projectBilledCostsWarningPercentage = ApplicationSettings::get()->project_billed_costs_warning_percentage;
        $projectMaterialCostsWarningPercentage = ApplicationSettings::get()->project_material_costs_warning_percentage;
        $projectWageCostsWarningPercentage = ApplicationSettings::get()->project_wage_costs_warning_percentage;

        return view('project.index')
            ->with(compact('projects'))
            ->with(compact('projectOverallCostsWarningPercentage'))
            ->with(compact('projectBilledCostsWarningPercentage'))
            ->with(compact('projectMaterialCostsWarningPercentage'))
            ->with(compact('projectWageCostsWarningPercentage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ProjectCreateRequest $request)
    {
        $validatedData = $request->validated();

        $currentCompany = null;

        $currencyUnit = ApplicationSettings::get()->currency_unit;

        if (isset($validatedData['company'])) {
            $currentCompany = Company::find($validatedData['company']);
        }

        $companies = Company::order()->get();

        return view('project.create')
            ->with('project', null)
            ->with(compact('currencyUnit'))
            ->with('currentCompany', $currentCompany)
            ->with('companies', $companies->toJson());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectStoreRequest $request)
    {
        $validatedData = $request->validated();

        $project = Project::make($validatedData);

        if(isset($validatedData['ends_on'])) {
            $project->is_pre_execution = false;
        }

        $project->save();

        return redirect()->route('projects.show', $project)->with('success', 'Das Projekt wurde erfolgreich angelegt.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, Request $request)
    {
        $financeRecordsCount = $project->financeGroup?->financeRecords()->count() ?? 0;

        $project
            ->loadCount('interimInvoices')
            ->loadCount('tasks')
            ->loadCount('memos')
            ->loadCount('serviceReports')
            ->loadCount('additionsReports')
            ->loadCount('inspectionReports')
            ->loadCount('flowMeterInspectionReports')
            ->loadCount('constructionReports');

        switch ($request->tab) {
            case 'overview':
                $currencyUnit = ApplicationSettings::get()->currency_unit;

                $financeData = Finances::getProjectData($project);
                $manualFinanceData = $project->financeGroup ? Finances::getGroupData($project->financeGroup) : null;

                return view('project.show_tab_overview')
                    ->with(compact('project'))
                    ->with('financeRecordsCount', $financeRecordsCount)
                    ->with('financeData', $financeData)
                    ->with('manualFinanceData', $manualFinanceData)
                    ->with(compact('currencyUnit'));

            case 'interim_invoices':
                if(Auth::user()->cannot('viewAny', InterimInvoice::class)) {
                    return redirect()->route('projects.show', [$project, 'tab' => 'overview']);
                }

                $interimInvoices = $project
                    ->interimInvoices()
                    ->order()
                    ->paginate(Auth::user()->settings->list_pagination_size)
                    ->appends($request->except('page'));

                return view('project.show_tab_interim_invoices')
                    ->with(compact('project'))
                    ->with('financeRecordsCount', $financeRecordsCount)
                    ->with(compact('interimInvoices'));

            case 'finances':
                if(Auth::user()->cannot('viewAny', FinanceRecord::class) ||
                    $project->include_in_finances) {
                    return redirect()->route('projects.show', [$project, 'tab' => 'overview']);
                }

                $financeGroup = $project
                    ->financeGroup?->loadSum('financeRecords', 'amount');

                $financeRecords = $financeGroup?->financeRecords()
                    ->order()
                    ->paginate(Auth::user()->settings->list_pagination_size)
                    ->appends($request->except('page')) ?? null;

                return view('project.show_tab_finances')
                    ->with(compact('project'))
                    ->with('financeRecordsCount', $financeRecordsCount)
                    ->with(compact('financeGroup'))
                    ->with(compact('financeRecords'));

            case 'tasks':
                if(Auth::user()->cannot('viewAny', Task::class)) {
                    return redirect()->route('projects.show', [$project, 'tab' => 'overview']);
                }

                Task::handleDefaultFilter($request);

                $tasks = $project->tasks()
                    ->filterPermissions()
                    ->filterSearch($request->search)
                    ->order($request->sort)
                    ->with('responsibleEmployee.person')
                    ->paginate(Auth::user()->settings->list_pagination_size)
                    ->appends($request->except('page'));

                return view('project.show_tab_tasks')
                    ->with(compact('project'))
                    ->with('financeRecordsCount', $financeRecordsCount)
                    ->with(compact('tasks'));

            case 'memos':
                if(Auth::user()->cannot('viewAny', Memo::class)) {
                    return redirect()->route('projects.show', [$project, 'tab' => 'overview']);
                }

                $memos = $project->memos()
                    ->filterPermissions()
                    ->filterSearch($request->search)
                    ->order($request->sort)
                    ->with('employeeComposer.person')
                    ->with('personRecipient')
                    ->paginate(Auth::user()->settings->list_pagination_size)
                    ->appends($request->except('page'));

                return view('project.show_tab_memos')
                    ->with(compact('project'))
                    ->with('financeRecordsCount', $financeRecordsCount)
                    ->with(compact('memos'));

            case 'service_reports':
                if(Auth::user()->cannot('viewAny', ServiceReport::class)) {
                    return redirect()->route('projects.show', [$project, 'tab' => 'overview']);
                }

                ServiceReport::handleDefaultFilter($request);

                $serviceReports = $project->serviceReports()
                    ->filterPermissions()
                    ->filterSearch($request->search)
                    ->order($request->sort)
                    ->with('employee.person')
                    ->withMin('services', 'provided_on')
                    ->withMax('services', 'provided_on')
                    ->withSum('services', 'hours')
                    ->withSum('services', 'kilometres')
                    ->paginate(Auth::user()->settings->list_pagination_size)
                    ->appends($request->except('page'));

                return view('project.show_tab_service_reports')
                    ->with(compact('project'))
                    ->with('financeRecordsCount', $financeRecordsCount)
                    ->with(compact('serviceReports'));

            case 'additions_reports':
                if(Auth::user()->cannot('viewAny', AdditionsReport::class)) {
                    return redirect()->route('projects.show', [$project, 'tab' => 'overview']);
                }

                AdditionsReport::handleDefaultFilter($request);

                $additionsReports = $project->additionsReports()
                    ->filterPermissions()
                    ->filterSearch($request->search)
                    ->order($request->sort)
                    ->with('employee.person')
                    ->paginate(Auth::user()->settings->list_pagination_size)
                    ->appends($request->except('page'));

                return view('project.show_tab_additions_reports')
                    ->with(compact('project'))
                    ->with('financeRecordsCount', $financeRecordsCount)
                    ->with(compact('additionsReports'));

            case 'inspection_reports':
                if(Auth::user()->cannot('viewAny', InspectionReport::class)) {
                    return redirect()->route('projects.show', [$project, 'tab' => 'overview']);
                }

                InspectionReport::handleDefaultFilter($request);

                $inspectionReports = $project->inspectionReports()
                    ->filterPermissions()
                    ->filterSearch($request->search)
                    ->order($request->sort)
                    ->with('employee.person')
                    ->paginate(Auth::user()->settings->list_pagination_size)
                    ->appends($request->except('page'));

                return view('project.show_tab_inspection_reports')
                    ->with(compact('project'))
                    ->with('financeRecordsCount', $financeRecordsCount)
                    ->with(compact('inspectionReports'));

            case 'flow_meter_inspection_reports':
                if(Auth::user()->cannot('viewAny', FlowMeterInspectionReport::class)) {
                    return redirect()->route('projects.show', [$project, 'tab' => 'overview']);
                }

                FlowMeterInspectionReport::handleDefaultFilter($request);

                $flowMeterInspectionReports = $project->flowMeterInspectionReports()
                    ->filterPermissions()
                    ->filterSearch($request->search)
                    ->order($request->sort)
                    ->with('employee.person')
                    ->paginate(Auth::user()->settings->list_pagination_size)
                    ->appends($request->except('page'));

                return view('project.show_tab_flow_meter_inspection_reports')
                    ->with(compact('project'))
                    ->with('financeRecordsCount', $financeRecordsCount)
                    ->with(compact('flowMeterInspectionReports'));

            case 'construction_reports':
                if(Auth::user()->cannot('viewAny', ConstructionReport::class)) {
                    return redirect()->route('projects.show', [$project, 'tab' => 'overview']);
                }

                ConstructionReport::handleDefaultFilter($request);

                $constructionReports = $project->constructionReports()
                    ->filterPermissions()
                    ->filterSearch($request->search)
                    ->order($request->sort)
                    ->with('employee.person')
                    ->paginate(Auth::user()->settings->list_pagination_size)
                    ->appends($request->except('page'));

                return view('project.show_tab_construction_reports')
                    ->with(compact('project'))
                    ->with('financeRecordsCount', $financeRecordsCount)
                    ->with(compact('constructionReports'));

            default:
                return redirect()->route('projects.show', [$project, 'tab' => 'overview']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $currencyUnit = ApplicationSettings::get()->currency_unit;

        $currentCompany = $project->company;
        $companies = Company::order()->get();

        return view('project.edit')
            ->with(compact('project'))
            ->with(compact('currencyUnit'))
            ->with('currentCompany', $currentCompany)
            ->with('companies', $companies->toJson());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectUpdateRequest $request, Project $project)
    {
        $validatedData = $request->validated();

        $project->update($validatedData);

        if($project->ends_on !== null) {
            $project->is_pre_execution = false;
            $project->save();
        }

        if($validatedData['include_in_finances']) {
            $project->financeGroup()->delete();
        }

        return redirect()->route('projects.show', $project)->with('success', 'Das Projekt wurde erfolgreich bearbeitet.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Project $project)
    {
        $redirect = $this->getConditionalRedirect($request->redirect, $project);

        $project->interimInvoices->delete();
        $project->delete();

        return $redirect->with('success', 'Das Projekt wurde erfolgreich entfernt.');
    }

    public function showDownload(Project $project)
    {
        $employees = Person::has('employee')->order()->get();
        $services = Service::order()->get();

        return view('project.download')->with(compact('project'))->with(compact('employees'))->with(compact('services'));
    }

    public function download(ProjectDownloadRequest $request, Project $project)
    {
        $validatedData = $request->validated();

        $start = isset($validatedData['start']) ? Carbon::parse($validatedData['start']) : null;
        $end = isset($validatedData['end']) ? Carbon::parse($validatedData['end']) : null;

        $startFormatted = optional($start)->format('Ymd') ?? '';
        $endFormatted = optional($end)->format('Ymd') ?? '';
        $rangeString = $startFormatted.($start && $end ? '-' : '').$endFormatted;

        $fileName = 'PR '.$project->name.($rangeString !== '' ? ' '.$rangeString : '').'.pdf';

        $report = $project->getReport($validatedData);
        $sums = $project->getReportSums($validatedData);

        $filterPeople = isset($validatedData['employee_ids']) ?
            Person::whereIn('id', $validatedData['employee_ids'])
                ->with('employee.user')
                ->get()
                ->sortBy('employee.user.username')
                ->values() :
            null;

        $filterServices = isset($validatedData['service_ids']) ?
            Service::whereIn('id', $validatedData['service_ids'])
                ->orderByDesc('type')
                ->orderBy('name')
                ->get() :
            null;

        $people = Person::whereIn('id', $report->pluck('employee_id')->unique())
                ->with('employee.user')
                ->get()
                ->sortBy('employee.user.username')
                ->values();

        $services = Service::whereIn('id', $report->pluck('service_id')->unique())
                ->orderByDesc('type')
                ->orderBy('name')
                ->get();

        $currencyUnit = ApplicationSettings::get()->currency_unit;

        $projectOverallCostsWarningPercentage = ApplicationSettings::get()->project_overall_costs_warning_percentage;
        $projectBilledCostsWarningPercentage = ApplicationSettings::get()->project_billed_costs_warning_percentage;
        $projectMaterialCostsWarningPercentage = ApplicationSettings::get()->project_material_costs_warning_percentage;
        $projectWageCostsWarningPercentage = ApplicationSettings::get()->project_wage_costs_warning_percentage;

        return (new Latex())
            ->binPath('/usr/bin/pdflatex')
            ->untilAuxSettles()
            ->view('latex.project_report', [
                'report' => $report,
                'sums' => $sums,
                'project' => $project,
                'filterPeople' => $filterPeople,
                'filterServices' => $filterServices,
                'people' => $people,
                'services' => $services,
                'start' => $start,
                'end' => $end,
                'currencyUnit' => $currencyUnit,
                'projectOverallCostsWarningPercentage' => $projectOverallCostsWarningPercentage,
                'projectBilledCostsWarningPercentage' => $projectBilledCostsWarningPercentage,
                'projectMaterialCostsWarningPercentage' => $projectMaterialCostsWarningPercentage,
                'projectWageCostsWarningPercentage' => $projectWageCostsWarningPercentage,
            ])
            ->download($fileName);
    }

    public function downloadList(ProjectDownloadListRequest $request)
    {
        $validatedData = $request->validated();

        $company = null;

        $companies = Company::order();

        if(isset($validatedData['company_id'])) {
            $companies = Company::whereId($validatedData['company_id']);
            $company = Company::find($validatedData['company_id']);
        }

        $companies = $companies
            ->with(['projects' => function ($query) {
                $query->order();
            }])->withCount('projects')
            ->get();

        $projectOverallCostsWarningPercentage = ApplicationSettings::get()->project_overall_costs_warning_percentage;
        $projectBilledCostsWarningPercentage = ApplicationSettings::get()->project_billed_costs_warning_percentage;
        $projectMaterialCostsWarningPercentage = ApplicationSettings::get()->project_material_costs_warning_percentage;
        $projectWageCostsWarningPercentage = ApplicationSettings::get()->project_wage_costs_warning_percentage;

        $fileName = 'PL '.(optional($company)->name ?? '').'.pdf';

        return (new Latex())
            ->binPath('/usr/bin/pdflatex')
            ->untilAuxSettles()
            ->view('latex.project_list', [
                'companies' => $companies,
                'company' => $company,
                'projectOverallCostsWarningPercentage' => $projectOverallCostsWarningPercentage,
                'projectBilledCostsWarningPercentage' => $projectBilledCostsWarningPercentage,
                'projectMaterialCostsWarningPercentage' => $projectMaterialCostsWarningPercentage,
                'projectWageCostsWarningPercentage' => $projectWageCostsWarningPercentage,
            ])
            ->download($fileName);
    }

    private function getConditionalRedirect(?string $target, Project $project)
    {
        switch ($target) {
            case 'company':
                $route = 'companies.show';
                $parameters = ['company' => $project->company, 'tab' => 'projects'];
                break;
            case 'show':
                $route = 'projects.show';
                $parameters = ['project' => $project];
                break;
            default:
                $route = 'projects.index';
                $parameters = [];
                break;
        }

        return redirect()->route($route, $parameters);
    }
}
