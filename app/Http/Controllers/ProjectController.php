<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $projects = Project::filter($request->input())
            ->order($request->input())
            ->with('company')
            ->withCount('tasks')
            ->withCount('memos')
            ->withCount('serviceReports')
            ->paginate(Auth::user()->settings->list_pagination_size)
            ->appends($request->except('page'));

        return view('project.index')->with(compact('projects'));
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
        $project->loadCount('tasks')->loadCount('memos')->loadCount('serviceReports');

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

                $tasks = Task::where('project_id', $project->id)
                    ->filter($request->input())
                    ->order($request->input())
                    ->with('responsibleEmployee.person')
                    ->paginate(Auth::user()->settings->list_pagination_size)
                    ->appends($request->except('page'));

                return view('project.show_tab_tasks')->with(compact('project'))->with(compact('tasks'));

            case 'memos':
                $memos = Memo::where('project_id', $project->id)
                    ->filter($request->input())
                    ->order($request->input())
                    ->with('employeeComposer.person')
                    ->with('personRecipient')
                    ->paginate(Auth::user()->settings->list_pagination_size)
                    ->appends($request->except('page'));

                return view('project.show_tab_memos')->with(compact('project'))->with(compact('memos'));

            case 'service_reports':
                if (! $request->has('search') && ! Auth::user()->settings->show_finished_items) {
                    $request->request->add(['search' => '!ist:erledigt']);
                } elseif ($request->has('search') && $request->search === '') {
                    $request->request->remove('search');
                }

                $serviceReports = ServiceReport::where('project_id', $project->id)
                    ->filter($request->input())
                    ->order($request->input())
                    ->with('employee.person')
                    ->withMin('services', 'provided_on')
                    ->withMax('services', 'provided_on')
                    ->withSum('services', 'hours')
                    ->withSum('services', 'kilometres')
                    ->paginate(Auth::user()->settings->list_pagination_size)
                    ->appends($request->except('page'));

                return view('project.show_tab_service_reports')->with(compact('project'))->with(compact('serviceReports'));

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
