<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Http\Requests\NoteCreateRequest;
use App\Http\Requests\NoteStoreRequest;
use App\Http\Requests\NoteUpdateRequest;
use App\Mail\NoteMail;
use App\Models\Note;
use App\Models\Person;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use ZsgsDesign\PDFConverter\Latex;

class NoteController extends Controller
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
        $this->authorizeResource(Note::class, 'note');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $notes = Auth::user()->employee->notes()
            ->filterSearch($request->search)
            ->order($request->sort)
            ->paginate(Auth::user()->settings->list_pagination_size)
            ->appends($request->except('page'));

        return view('note.index')->with(compact('notes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(NoteCreateRequest $request)
    {
        $validatedData = $request->validated();

        $templateNote = null;

        if(isset($validatedData['template'])) {
            $templateNote = Note::find($validatedData['template']);

            if(!$templateNote) {
                return redirect()
                    ->route('notes.create')
                    ->with('warning', 'Die angegebene Notiz existiert nicht.');
            }

            if(Auth::user()->cannot('view', $templateNote)) {
                return redirect()
                    ->route('notes.create')
                    ->with('danger', 'Du kannst diese Notiz nicht kopieren.');
            }
        }

        return view('note.create')
            ->with('note', $templateNote)
            ->with('currentAttachments', null);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NoteStoreRequest $request)
    {
        $note = Note::make($request->validated());
        $note->employee()->associate(Auth::user()->employee);
        $note->save();

        if ($request->new_attachments) {
            $note->addAttachments($request->new_attachments);
        }

        return redirect()->route('notes.show', $note)->with('success', 'Die Notiz wurde erfolgreich angelegt.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        $note->load('media');

        return view('note.show')->with(compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function edit(Note $note)
    {
        $currentAttachments = $note->attachmentsWithUrl();

        return view('note.edit')
            ->with('note', $note)
            ->with('currentAttachments', $currentAttachments->toJson());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(NoteUpdateRequest $request, Note $note)
    {
        $note->update($request->validated());

        if ($request->remove_attachments) {
            $note->deleteAttachments($request->remove_attachments);

            $note->touch();
        }

        if ($request->new_attachments) {
            $note->addAttachments($request->new_attachments);

            $note->touch();
        }

        return redirect()->route('notes.show', $note)->with('success', 'Die Notiz wurde erfolgreich bearbeitet.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        $note->deleteAttachments();
        $note->delete();

        return redirect()->route('notes.index')->with('success', 'Die Notiz wurde erfolgreich entfernt.');
    }

    public function showEmail(Request $request, Note $note): View
    {
        $note->load('media');

        $people = Person::whereNotNull('email')->order()->get();

        return view('note.email')
            ->with('note', $note)
            ->with('people', $people->toJson())
            ->with('currentTo', null)
            ->with('currentCC', null)
            ->with('currentBCC', null);
    }

    public function email(EmailRequest $request, Note $note): RedirectResponse
    {
        $validatedData = $request->validated();

        $mail = Mail::to($request->email_to);
        if ($request->email_cc) {
            $mail = $mail->cc($request->email_cc);
        }
        if ($request->email_bcc) {
            $mail = $mail->bcc($request->email_bcc);
        }

        $mail->send(new NoteMail($note));

        return $this->getConditionalRedirect($request->redirect, $note)
            ->with('success', 'Die Notiz wurde erfolgreich gesendet.');
    }

    public function download(Request $request, Note $note)
    {
        return (new Latex())
            ->binPath('/usr/bin/pdflatex')
            ->untilAuxSettles()
            ->view('latex.note', ['note' => $note])
            ->download('NO '.$note->created_at->format('Y-m-d Hi').'.pdf');
    }

    public function downloadList(Request $request)
    {
        $notes = Auth::user()->employee->notes()
            ->order()
            ->with('employee.person');

        $fileName = 'Notizbuch '.Auth::user()->employee->person->name.'.pdf';

        return (new Latex())
            ->binPath('/usr/bin/pdflatex')
            ->untilAuxSettles()
            ->view('latex.note_list', [
                'notes' => $notes,
            ])
            ->download($fileName);
    }

    private function getConditionalRedirect(?string $target, Note $note): RedirectResponse
    {
        switch ($target) {
            case 'show':
                $route = 'notes.show';
                $parameters = ['note' => $note];
                break;
            default:
                $route = 'notes.index';
                $parameters = [];
                break;
        }

        return redirect()->route($route, $parameters);
    }
}
