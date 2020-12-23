<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Models\Company;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $projects = Project::order($request->input())
            ->with('company')
            ->withCount('tasks')
            ->withCount('memos')
            ->withCount('serviceReports')
            ->paginate(15)
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

        if ($request->filled('company')) {
            $currentCompany = Company::find($request->company);
        }

        $companies = Company::order()->get();

        return view('project.create')
            ->with('project', null)
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

        Project::create($validatedData);

        return redirect()->route('projects.index')->with('success', 'Das Projekt wurde erfolgreich angelegt.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, Request $request)
    {
        $input = $request->input();
        $project->loadCount('tasks')->loadCount('memos')->loadCount('serviceReports');

        switch ($request->tab) {
            case 'overview':
                return view('project.show_tab_overview')->with(compact('project'));

            case 'tasks':
                $project->load(['tasks' => function ($query) use ($input) {
                    $query
                        ->with('responsibleEmployee.person')
                        ->order($input);
                }])->paginate(15)->appends($request->except('page'));

                return view('project.show_tab_tasks')->with(compact('project'));

            case 'memos':
                $project->load(['memos' => function ($query) use ($input) {
                    $query
                        ->with('employeeComposer.person')
                        ->with('personRecipient')
                        ->order($input);
                }])->paginate(15)->appends($request->except('page'));

                return view('project.show_tab_memos')->with(compact('project'));

            case 'service_reports':
                $project->load(['serviceReports' => function ($query) use ($input) {
                    $query
                        ->with('employee.person')
                        ->withMin('services', 'provided_on')
                        ->withMax('services', 'provided_on')
                        ->withSum('services', 'hours')
                        ->order($input);
                }])->paginate(15)->appends($request->except('page'));

                return view('project.show_tab_service_reports')->with(compact('project'));

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
        $currentCompany = $project->company;
        $companies = Company::order()->get();

        return view('project.edit')
            ->with('project', $project)
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

        return redirect()->route('projects.index')->with('success', 'Das Projekt wurde erfolgreich bearbeitet.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Das Projekt wurde erfolgreich entfernt.');
    }
}
