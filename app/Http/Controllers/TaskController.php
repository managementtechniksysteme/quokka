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
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use ZsgsDesign\PDFConverter\Latex;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (! $request->has('search') && ! Auth::user()->settings->show_finished_items) {
            $request->request->add(['search' => '!ist:erledigt']);
        } elseif ($request->has('search') && $request->search === '') {
            $request->request->remove('search');
        }

        $tasks = Task::filter($request->input())->order($request->input())
            ->with('project')
            ->with('responsibleEmployee.person')
            ->paginate(Auth::user()->settings->list_pagination_size)
            ->appends($request->except('page'));

        return view('task.index')->with(compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $currentProject = null;

        if ($request->filled('project')) {
            $currentProject = Project::find($request->project);
        }

        $projects = Project::order()->get();
        $employees = Person::has('employee')->order()->get();

        return view('task.create')
            ->with('task', null)
            ->with('currentProject', $currentProject)
            ->with('projects', $projects->toJson())
            ->with('currentResponsibleEmployee', null)
            ->with('currentInvolvedEmployees', null)
            ->with('employees', $employees->toJson())
            ->with('currentAttachments', null);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskStoreRequest $request)
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

            $task->involvedEmployees()->attach($employees);
        }

        if ($request->new_attachments) {
            $task->addAttachments($request->new_attachments);
        }

        event(new TaskCreatedEvent($task));

        return redirect()->route('tasks.index')->with('success', 'Die Aufgabe wurde erfolgreich angelegt.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task, TaskComment $comment)
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(TaskUpdateRequest $request, Task $task)
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

            $task->involvedEmployees()->sync($employees);
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

        return redirect()->route('tasks.index')->with('success', 'Die Aufgabe wurde erfolgreich bearbeitet.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Die Aufgabe wurde erfolgreich entfernt.');
    }

    public function showEmail(Request $request, Task $task)
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

    public function email(EmailRequest $request, Task $task)
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

        return redirect()->route('tasks.index')->with('success', 'Die Aufgabe wurde erfolgreich gesendet.');
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
}
