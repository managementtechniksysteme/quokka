<?php

namespace App\Http\Controllers;

use App\Events\TaskCreatedEvent;
use App\Events\TaskUpdatedEvent;
use App\Http\Requests\EmailRequest;
use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskDownloadListRequest;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Mail\TaskMail;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Person;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskComment;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use ZsgsDesign\PDFConverter\Latex;

class TaskController extends Controller
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
        $this->authorizeResource(Task::class, 'task');
    }

    public function index(Request $request): View
    {
        Task::handleDefaultFilter($request);

        $tasks = Task::filterPermissions()
            ->filterSearch($request->search)
            ->order($request->sort)
            ->with('project')
            ->with('responsibleEmployee.person')
            ->paginate(Auth::user()->settings->list_pagination_size)
            ->appends($request->except('page'));

        return view('task.index')->with(compact('tasks'));
    }

    public function create(TaskCreateRequest $request): RedirectResponse|View
    {
        $templateTask = null;
        $currentProject = null;
        $currentResponsibleEmployee = null;
        $currentInvolvedEmployees = null;

        $validatedData = $request->validated();

        if(isset($validatedData['template'])) {
            $templateTask = Task::find($validatedData['template']);

            if(!$templateTask) {
                return redirect()
                    ->route('tasks.create')
                    ->with('warning', 'Die angegebene Aufgabe existiert nicht.');
            }

            if(Auth::user()->cannot('view', $templateTask)) {
                return redirect()
                    ->route('inspection-reports.create')
                    ->with('danger', 'Du kannst diese Aufgabe nicht kopieren.');
            }

            $templateTask->load('project')
                ->load('responsibleEmployee.person')
                ->load('involvedEmployees.person');

            $currentProject = $templateTask->project;
            $currentResponsibleEmployee = $templateTask->responsibleEmployee->person;
            $currentInvolvedEmployees = Person::order()->find($templateTask->involvedEmployees->pluck('person_id')) ?? null;
        }
        elseif (isset($validatedData['project'])) {
            $currentProject = Project::find($validatedData['project']);
        }

        $projects = Project::order()->get();

        $currentResponsibleEmployee = $currentResponsibleEmployee ?? Auth::user()->employee->person;
        $employees = Person::has('employee')->order()->get();

        return view('task.create')
            ->with('task', $templateTask)
            ->with('currentProject', $currentProject)
            ->with('projects', $projects->toJson())
            ->with('currentResponsibleEmployee', optional($currentResponsibleEmployee)->toJson())
            ->with('currentInvolvedEmployees', optional($currentInvolvedEmployees)->toJson())
            ->with('employees', $employees->toJson())
            ->with('currentAttachments', null);
    }

    public function store(TaskStoreRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $task = Task::create($validatedData);

        if ($task->status !== 'finished' && $task->ends_on) {
            $task->ends_on = null;
            $task->save();
        }

        if ($task->status == 'finished' && ! $task->ends_on) {
            $task->starts_on = $task->starts_on ?? Carbon::now();
            $task->ends_on = Carbon::now();
            $task->save();
        }

        if ($request->filled('involved_ids')) {
            if (($responsibleEmployee = array_search($task->employee_id, $request->involved_ids)) !== false) {
                $employees = Employee::find(Arr::except($request->involved_ids, $responsibleEmployee));
            } else {
                $employees = Employee::find($request->involved_ids);
            }

            $task->involvedEmployees()->attach($employees, ['employee_type' => 'involved']);
        }

        if ($request->new_attachments) {
            $task->addAttachments($request->new_attachments);
        }

        event(new TaskCreatedEvent($task));

        return redirect()->route('tasks.show', $task)->with('success', 'Die Aufgabe wurde erfolgreich angelegt.');
    }

    public function show(Task $task, TaskComment $comment): View
    {
        $task->load('project')
            ->load(['responsibleEmployee.person' => function ($query) {
                $query->order();
            }])
            ->load(['involvedEmployees.person' => function ($query) {
                $query->order();
            }]);

        $commentSortKey =
            Auth::user()->settings->task_comments_sort_newest_first ? 'created_at-desc' : 'created_at-asc';

        $comments = TaskComment::where('task_id', $task->id)
            ->order($commentSortKey)
            ->with('employee.user.settings')
            ->with('media')
            ->paginate(Auth::user()->settings->list_pagination_size);

        return view('task.show')->with(compact('task'))->with(compact('comments'));
    }

    public function edit(Task $task): View
    {
        $currentProject = $task->project;
        $projects = Project::order()->get();

        $currentResponsibleEmployee = $task->responsibleEmployee->person;
        $employees = Person::has('employee')->order()->get();

        $currentInvolvedEmployees = Person::order()->find($task->involvedEmployees->pluck('person_id')) ?? null;

        $currentAttachments = $task->attachmentsWithUrl();

        return view('task.edit')
            ->with('task', $task)
            ->with('currentProject', $currentProject)
            ->with('projects', $projects->toJson())
            ->with('currentResponsibleEmployee', $currentResponsibleEmployee)
            ->with('currentInvolvedEmployees', $currentInvolvedEmployees->toJson())
            ->with('employees', $employees->toJson())
            ->with('currentAttachments', $currentAttachments->toJson());
    }

    public function update(TaskUpdateRequest $request, Task $task): RedirectResponse
    {
        $validatedData = $request->validated();

        $task->update($validatedData);

        if ($task->status !== 'finished' && $task->ends_on) {
            $task->ends_on = null;
            $task->save();
        }

        if ($task->status === 'finished' && ! $task->ends_on) {
            $task->starts_on = $task->starts_on ?? Carbon::now();
            $task->ends_on = Carbon::now();
            $task->save();
        }

        if ($request->filled('involved_ids')) {
            if (($responsibleEmployee = array_search($task->employee_id, $request->involved_ids)) !== false) {
                $employees = Employee::find(Arr::except($request->involved_ids, $responsibleEmployee));
            } else {
                $employees = Employee::find($request->involved_ids);
            }

            $touched = $task->involvedEmployees()->syncWithPivotValues($employees, ['employee_type' => 'involved']);

            if($touched['attached'] || $touched['detached'] || $touched['updated']) {
                $task->touch();
            }
        } else {
            $touched = $task->involvedEmployees()->detach();

            if($touched > 0) {
                $task->touch();
            }
        }

        if ($request->remove_attachments) {
            $task->deleteAttachments($request->remove_attachments);

            $task->touch();
        }

        if ($request->new_attachments) {
            $task->addAttachments($request->new_attachments);

            $task->touch();
        }

        if($task->wasChanged()) {
            event(new TaskUpdatedEvent($task));
        }

        return redirect()->route('tasks.show', $task)->with('success', 'Die Aufgabe wurde erfolgreich bearbeitet.');
    }

    public function destroy(Request $request, Task $task): RedirectResponse
    {
        $task->involvedEmployees()->detach();
        $task->deleteAttachments();
        $task->delete();

        return $this->getConditionalRedirect($request->redirect, $task)
            ->with('success', 'Die Aufgabe wurde erfolgreich entfernt.');
    }

    public function showEmail(Request $request, Task $task): View
    {
        $currentTo = $task->involvedEmployees->pluck('person');
        $people = Person::whereNotNull('email')->order()->get();

        return view('task.email')
            ->with('task', $task)
            ->with('people', $people->toJson())
            ->with('currentTo', $currentTo->toJson())
            ->with('currentCC', null)
            ->with('currentBCC', null);
    }

    public function email(EmailRequest $request, Task $task): RedirectResponse
    {
        $validatedData = $request->validated();

        $task
            ->load('project')
            ->load('responsibleEmployee')
            ->load('involvedEmployees');

        $mail = Mail::to($request->email_to);
        if ($request->email_cc) {
            $mail = $mail->cc($request->email_cc);
        }
        if ($request->email_bcc) {
            $mail = $mail->bcc($request->email_bcc);
        }

        $mail->send(new TaskMail($task));

        return $this->getConditionalRedirect($request->redirect, $task)
            ->with('success', 'Die Aufgabe wurde erfolgreich gesendet.');
    }

    public function download(Request $request, Task $task)
    {
        $task
            ->load('project')
            ->load('responsibleEmployee')
            ->load('involvedEmployees');

        return (new Latex())
            ->binPath('/usr/bin/pdflatex')
            ->untilAuxSettles()
            ->view('latex.task', ['task' => $task])
            ->download('AU '.$task->name.'.pdf');
    }

    public function finish(Request $request, Task $task): RedirectResponse
    {
        $task->status = 'finished';

        if(!$task->ends_on) {
            $task->starts_on = $task->starts_on ?? Carbon::now();
            $task->ends_on = Carbon::now();
        }

        $task->save();

        return $this->getConditionalRedirect($request->redirect, $task)
            ->with('success', 'Die Aufgabe wurde erfolgreich erledigt.');
    }

    public function downloadList(TaskDownloadListRequest $request)
    {
        $validatedData = $request->validated();

        $company = null;
        $project = null;

        $companies = Company::order();

        if(isset($validatedData['company_id'])) {
            $companies = Company::whereId($validatedData['company_id']);
            $company = Company::find($validatedData['company_id']);
        } else if (isset($validatedData['project_id'])) {
            $project = Project::find($validatedData['project_id']);
            $companies = Company::whereId($project->company_id);
        }

        $companies = $companies
            ->with(['projects' => function($query) use ($project) {
                $query
                    ->order()
                    ->when($project, function($query) use ($project) {
                        $query->whereId($project->id);
                    })
                    ->with(['tasks' => function($query) {
                        $query
                            ->filterPermissions()
                            ->with('responsibleEmployee.user')
                            ->orderByRaw('field(status, "new", "in progress", "finished")')
                            ->orderByRaw('field(priority, "high", "medium", "low")')
                            ->orderBy('due_on', 'desc');
                    }])
                    ->withCount('tasks');
            }])
            ->withCount('projects')
            ->get();

        $totalTasks = $companies->reduce(function ($carry, $company) {
            return $carry + $company->projects->sum('tasks_count');
        });

        $fileName = 'AL' . optional($company)->name ?? '' . optional($project)->name ?? '';

        return (new Latex())
            ->binPath('/usr/bin/pdflatex')
            ->untilAuxSettles()
            ->view('latex.task_list', [
                'companies' => $companies,
                'company' => $company,
                'project' => $project,
                'totalTasks' => $totalTasks,
            ])
            ->download($fileName);
    }

    private function getConditionalRedirect(?string $target, Task $task): RedirectResponse
    {
        switch ($target) {
            case 'project':
                $route = 'projects.show';
                $parameters = ['project' => $task->project, 'tab' => 'tasks'];
                break;
            case 'show':
                $route = 'tasks.show';
                $parameters = ['task' => $task];
                break;
            default:
                $route = 'tasks.index';
                $parameters = [];
                break;
        }

        return redirect()->route($route, $parameters);
    }
}
