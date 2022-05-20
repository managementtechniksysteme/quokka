<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Models\AdditionsReport;
use App\Models\ApplicationSettings;
use App\Models\Company;
use App\Models\Memo;
use App\Models\Project;
use App\Models\ServiceReport;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $projects = Project::filterSearch($request->input())
            ->order($request->input())
            ->with('company')
            ->withCount('tasks')
            ->withCount('memos')
            ->withCount('serviceReports')
            ->paginate(Auth::user()->settings->list_pagination_size)
            ->appends($request->except('page'));

        $projectOverwallCostsWarningPercentage = ApplicationSettings::get()->project_overall_costs_warning_percentage;
        $projectMaterialCostsWarningPercentage = ApplicationSettings::get()->project_material_costs_warning_percentage;
        $projectWageCostsWarningPercentage = ApplicationSettings::get()->project_wage_costs_warning_percentage;

        return view('project.index')
            ->with(compact('projects'))
            ->with(compact('projectOverwallCostsWarningPercentage'))
            ->with(compact('projectMaterialCostsWarningPercentage'))
            ->with(compact('projectWageCostsWarningPercentage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $currentCompany = null;

        $currencyUnit = ApplicationSettings::get()->currency_unit;

        if ($request->filled('company')) {
            $currentCompany = Company::find($request->company);
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

        $project = Project::create($validatedData);

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
        $project
            ->loadCount('tasks')
            ->loadCount('memos')
            ->loadCount('serviceReports')
            ->loadCount('additionsReports');

        switch ($request->tab) {
            case 'overview':
                $currencyUnit = ApplicationSettings::get()->currency_unit;
                return view('project.show_tab_overview')->with(compact('project'))->with(compact('currencyUnit'));

            case 'tasks':
                if (! $request->has('search') && ! Auth::user()->settings->show_finished_items) {
                    $request->request->add(['search' => '!ist:erledigt']);
                } elseif ($request->has('search') && $request->search === '') {
                    $request->request->remove('search');
                }

                $tasks = $project->tasks()
                    ->filterPermissions()
                    ->filterSearch($request->input())
                    ->order($request->input())
                    ->with('responsibleEmployee.person')
                    ->paginate(Auth::user()->settings->list_pagination_size)
                    ->appends($request->except('page'));

                return view('project.show_tab_tasks')->with(compact('project'))->with(compact('tasks'));

            case 'memos':
                $memos = $project->memos()
                    ->filterPermissions()
                    ->filterSearch($request->input())
                    ->order($request->input())
                    ->with('employeeComposer.person')
                    ->with('personRecipient')
                    ->paginate(Auth::user()->settings->list_pagination_size)
                    ->appends($request->except('page'));

                return view('project.show_tab_memos')->with(compact('project'))->with(compact('memos'));

            case 'service_reports':
                if (! $request->has('search')) {
                    $search = '';

                    if (! Auth::user()->settings->show_finished_items) {
                        $search .= '!ist:erledigt ';
                    }
                    if (Auth::user()->settings->show_only_own_reports) {
                        $search .= 't:' . Auth::user()->username . ' ';
                    }

                    $search = trim($search);
                    if($search !== '') {
                        $request->request->add(['search' => $search]);
                    }
                } elseif ($request->has('search') && $request->search === '') {
                    $request->request->remove('search');
                }

                $serviceReports = $project->serviceReports()
                    ->filterPermissions()
                    ->filterSearch($request->input())
                    ->order($request->input())
                    ->with('employee.person')
                    ->withMin('services', 'provided_on')
                    ->withMax('services', 'provided_on')
                    ->withSum('services', 'hours')
                    ->withSum('services', 'kilometres')
                    ->paginate(Auth::user()->settings->list_pagination_size)
                    ->appends($request->except('page'));

                return view('project.show_tab_service_reports')->with(compact('project'))->with(compact('serviceReports'));

            case 'additions_reports':
                if (! $request->has('search')) {
                    $search = '';

                    if (! Auth::user()->settings->show_finished_items) {
                        $search .= '!ist:erledigt ';
                    }
                    if (Auth::user()->settings->show_only_own_reports) {
                        $search .= 't:' . Auth::user()->username . ' ';
                    }

                    $search = trim($search);
                    if($search !== '') {
                        $request->request->add(['search' => $search]);
                    }
                } elseif ($request->has('search') && $request->search === '') {
                    $request->request->remove('search');
                }

                $additionsReports = $project->additionsReports()
                    ->filterPermissions()
                    ->filterSearch($request->input())
                    ->order($request->input())
                    ->with('employee.person')
                    ->paginate(Auth::user()->settings->list_pagination_size)
                    ->appends($request->except('page'));

                return view('project.show_tab_additions_reports')->with(compact('project'))->with(compact('additionsReports'));

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

        $project->delete();

        return $redirect->with('success', 'Das Projekt wurde erfolgreich entfernt.');
    }

    private function getConditionalRedirect($target, $project)
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
