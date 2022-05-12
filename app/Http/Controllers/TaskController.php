<?php

namespace App\Http\Controllers;

use App\Events\TaskCreatedEvent;
use App\Events\TaskUpdatedEvent;
use App\Http\Requests\EmailRequest;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Mail\TaskMail;
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
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
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

    public function __construct()
    {
        $this->authorizeResource(Task::class, 'task');
    }

    public function index(Request $request): View
    {
        if (! $request->has('search') && ! Auth::user()->settings->show_finished_items) {
            $request->request->add(['search' => '!ist:erledigt']);
        } elseif ($request->has('search') && $request->search === '') {
            $request->request->remove('search');
        }

        $tasks = Task::filterPermissions()
            ->filterSearch($request->input())
            ->order($request->input())
            ->with('project')
            ->with('responsibleEmployee.person')
            ->paginate(Auth::user()->settings->list_pagination_size)
            ->appends($request->except('page'));

        return view('task.index')->with(compact('tasks'));
    }

    public function create(Request $request): View
    {
        $templateTask = null;
        $currentProject = null;
        $currentResponsibleEmployee = null;
        $currentInvolvedEmployees = null;

        if($request->filled('template')) {
            $templateTask = Task::find($request->template)
                ->load('project')
                ->load('responsibleEmployee.person')
                ->load('involvedEmployees.person');

            $currentProject = $templateTask->project;
            $currentResponsibleEmployee = $templateTask->responsibleEmployee->person;
            $currentInvolvedEmployees = Person::order()->find($templateTask->involvedEmployees->pluck('person_id')) ?? null;
        }
        elseif ($request->filled('project')) {
            $currentProject = Project::find($request->project);
        }

        $projects = Project::order()->get();
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
            ->load('responsibleEmployee.person')
            ->load('involvedEmployees.person');

        $commentSortKey =
            Auth::user()->settings->task_comments_sort_newest_first ?
                ['sort' => 'created_at-desc'] : ['sort' => 'created_at-asc'];

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

            $task->involvedEmployees()->syncWithPivotValues($employees, ['employee_type' => 'involved']);
        } else {
            $task->involvedEmployees()->detach();
        }

        if ($request->remove_attachments) {
            $task->deleteAttachments($request->remove_attachments);
        }

        if ($request->new_attachments) {
            $task->addAttachments($request->new_attachments);
        }

        event(new TaskUpdatedEvent($task));

        return redirect()->route('tasks.show', $task)->with('success', 'Die Aufgabe wurde erfolgreich bearbeitet.');
    }

    public function destroy(Request $request, Task $task): RedirectResponse
    {
        $task->involvedEmployees()->detach();
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

    public function finish(Request $request, Task $task)
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

    private function getConditionalRedirect($target, $task)
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
