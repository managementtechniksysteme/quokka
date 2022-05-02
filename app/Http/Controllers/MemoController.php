<?php

namespace App\Http\Controllers;

use App\Events\MemoCreatedEvent;
use App\Events\MemoUpdatedEvent;
use App\Http\Requests\EmailRequest;
use App\Http\Requests\MemoStoreRequest;
use App\Http\Requests\MemoUpdateRequest;
use App\Mail\MemoMail;
use App\Models\Memo;
use App\Models\Person;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use ZsgsDesign\PDFConverter\Latex;

class MemoController extends Controller
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
        $this->authorizeResource(Memo::class, 'memo');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $memos = Memo::filterSearch($request->input())
            ->order($request->input())
            ->with('employeeComposer.person')
            ->with('personRecipient')
            ->paginate(Auth::user()->settings->list_pagination_size)
            ->appends($request->except('page'));

        return view('memo.index')->with(compact('memos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $templateMemo = null;
        $currentProject = null;
        $currentEmployeeComposer = null;
        $currentPersonRecepient = null;
        $currentPresentPeople = null;
        $currentNotifiedPeople = null;

        if($request->filled('template')) {
            $templateMemo = Memo::find($request->template)
                ->load('project')
                ->load('employeeComposer.person')
                ->load('personRecipient')
                ->load('presentPeople')
                ->load('notifiedPeople');

            $currentProject = $templateMemo->project;
            $currentEmployeeComposer = $templateMemo->employeeComposer->person;
            $currentPersonRecepient = $templateMemo->personRecipient;
            $currentPresentPeople = $templateMemo->presentPeople;
            $currentNotifiedPeople = $templateMemo->notifiedPeople;
        }
        else if ($request->filled('project')) {
            $currentProject = Project::find($request->project);
        }

        $projects = Project::order()->get();
        $employees = Person::has('employee')->order()->get();
        $people = Person::order()->get();

        return view('memo.create')
            ->with('memo', $templateMemo)
            ->with('currentProject', $currentProject)
            ->with('projects', $projects->toJson())
            ->with('currentEmployeeComposer', optional($currentEmployeeComposer)->toJson())
            ->with('employees', $employees->toJson())
            ->with('currentPersonRecipient', optional($currentPersonRecepient)->toJson())
            ->with('currentPresentPeople', optional($currentPresentPeople)->toJson())
            ->with('currentNotifiedPeople', optional($currentNotifiedPeople)->toJson())
            ->with('people', $people->toJson())
            ->with('currentAttachments', null);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MemoStoreRequest $request)
    {
        $validatedData = $request->validated();

        $memo = Memo::make($validatedData);
        $memo->number = 1;

        $latestMemo = Memo::where('project_id', $request->project_id)->latest('number')->first();

        if ($latestMemo) {
            $memo->number = $latestMemo->number + 1;
        }

        $memo->save();

        if ($request->filled('present_ids')) {
            $memo->presentPeople()->attach(Person::find($request->present_ids), ['person_type' => 'present']);
        }

        if ($request->filled('notified_ids')) {
            if (($recipient = array_search($memo->person_id, $request->notified_ids)) !== false) {
                $notifiedPeople = Person::find(Arr::except($request->notified_ids, $recipient));
            } else {
                $notifiedPeople = Person::find($request->notified_ids);
            }

            $memo->notifiedPeople()->attach($notifiedPeople, ['person_type' => 'notified']);
        }

        if ($request->new_attachments) {
            $memo->addAttachments($request->new_attachments);
        }

        event(new MemoCreatedEvent($memo));

        return redirect()->route('memos.show', $memo)->with('success', 'Der Aktenvermerk wurde erfolgreich angelegt.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Memo  $memo
     * @return \Illuminate\Http\Response
     */
    public function show(Memo $memo)
    {
        $memo->load('project')
            ->load('employeeComposer')
            ->load('personRecipient')
            ->load(['presentPeople' => function ($query) {
                $query->order();
            }])
            ->load(['notifiedPeople' => function ($query) {
                $query->order();
            }])
            ->load('media');

        return view('memo.show')->with(compact('memo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Memo  $memo
     * @return \Illuminate\Http\Response
     */
    public function edit(Memo $memo)
    {
        $currentProject = $memo->project;
        $projects = Project::order()->get();

        $currentEmployeeComposer = $memo->employeeComposer->person;
        $employees = Person::has('employee')->order()->get();

        $currentPersonRecipient = $memo->personRecipient ?? null;
        $currentPresentPeople = $memo->presentPeople ?? null;
        $currentNotifiedPeople = $memo->notifiedPeople ?? null;
        $people = Person::order()->get();

        $currentAttachments = $memo->attachmentsWithUrl();

        return view('memo.edit')
            ->with('memo', $memo)
            ->with('currentProject', $currentProject)
            ->with('projects', $projects->toJson())
            ->with('currentEmployeeComposer', $currentEmployeeComposer)
            ->with('employees', $employees->toJson())
            ->with('currentPersonRecipient', $currentPersonRecipient)
            ->with('currentPresentPeople', $currentPresentPeople)
            ->with('currentNotifiedPeople', $currentNotifiedPeople)
            ->with('people', $people->toJson())
            ->with('currentAttachments', $currentAttachments->toJson());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Memo  $memo
     * @return \Illuminate\Http\Response
     */
    public function update(MemoUpdateRequest $request, Memo $memo)
    {
        $validatedData = $request->validated();

        $memo->update($validatedData);

        if (! $request->filled('person_id')) {
            $memo->personRecipient()->disassociate();
            $memo->save();
        }

        if ($request->filled('present_ids')) {
            $memo->presentPeople()->syncWithPivotValues($request->present_ids, ['person_type' => 'present']);
        } else {
            $memo->presentPeople()->detach();
        }

        if ($request->filled('notified_ids')) {
            if (($recipient = array_search($memo->person_id, $request->notified_ids)) !== false) {
                $notifiedPeople = Person::find(Arr::except($request->notified_ids, $recipient));
            } else {
                $notifiedPeople = Person::find($request->notified_ids);
            }

            $memo->notifiedPeople()->syncWithPivotValues($notifiedPeople, ['person_type' => 'notified']);
        } else {
            $memo->notifiedPeople()->detach();
        }

        if ($request->remove_attachments) {
            $memo->deleteAttachments($request->remove_attachments);
        }

        if ($request->new_attachments) {
            $memo->addAttachments($request->new_attachments);
        }

        event(new MemoUpdatedEvent($memo));

        return redirect()->route('memos.show', $memo)->with('success', 'Der Aktenvermerk wurde erfolgreich bearbeitet.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Memo  $memo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Memo $memo)
    {
        $memo->presentPeople()->detach();
        $memo->notifiedPeople()->detach();
        $memo->delete();

        return $this->getConditionalRedirect($request->redirect, $memo)
            ->with('success', 'Die Aktenvermerk wurde erfolgreich entfernt.');
    }

    public function showEmail(Request $request, Memo $memo)
    {
        $memo->load('media');

        $currentTo = collect($memo->personRecipient ? [$memo->personRecipient] : []);
        $currentCC = $memo->notifiedPeople;
        $people = Person::whereNotNull('email')->order()->get();

        return view('memo.email')
            ->with('memo', $memo)
            ->with('people', $people->toJson())
            ->with('currentTo', $currentTo->toJson())
            ->with('currentCC', $currentCC->toJson())
            ->with('currentBCC', null);
    }

    public function email(EmailRequest $request, Memo $memo)
    {
        $validatedData = $request->validated();

        $attachments = $request->attachment_ids ? Media::find($request->attachment_ids) : null;

        $memo
            ->load('project')
            ->load('employeeComposer')
            ->load('personRecipient')
            ->load(('presentPeople'))
            ->load('notifiedPeople');

        $mail = Mail::to($request->email_to);
        if ($request->email_cc) {
            $mail = $mail->cc($request->email_cc);
        }
        if ($request->email_bcc) {
            $mail = $mail->bcc($request->email_bcc);
        }

        $mail->send(new MemoMail($memo, $attachments));

        return $this->getConditionalRedirect($request->redirect, $memo)
            ->with('success', 'Der Aktenvermerk wurde erfolgreich gesendet.');
    }

    public function download(Request $request, Memo $memo)
    {
        $memo
            ->load('project')
            ->load('employeeComposer')
            ->load('personRecipient')
            ->load('presentPeople')
            ->load('notifiedPeople');

        return (new Latex())
            ->binPath('/usr/bin/pdflatex')
            ->untilAuxSettles()
            ->view('latex.memo', ['memo' => $memo])
            ->download('AV '.$memo->project->name.' #'.$memo->number.'.pdf');
    }

    private function getConditionalRedirect($target, $memo)
    {
        switch ($target) {
            case 'project':
                $route = 'projects.show';
                $parameters = ['project' => $memo->project, 'tab' => 'memos'];
                break;
            case 'show':
                $route = 'memos.show';
                $parameters = ['memo' => $memo];
                break;
            default:
                $route = 'memos.index';
                $parameters = [];
                break;
        }

        return redirect()->route($route, $parameters);
    }
}
