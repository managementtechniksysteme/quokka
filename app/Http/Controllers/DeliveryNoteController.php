<?php

namespace App\Http\Controllers;

use App\Events\DeliveryNoteSignedEvent;
use App\Http\Requests\DeliveryNoteCreateRequest;
use App\Http\Requests\DeliveryNoteStoreRequest;
use App\Http\Requests\DeliveryNoteUpdateRequest;
use App\Http\Requests\EmailRequest;
use App\Http\Requests\SignRequest;
use App\Http\Requests\SingleEmailRequest;
use App\Mail\DeliveryNoteDownloadRequestMail;
use App\Mail\DeliveryNoteMail;
use App\Mail\DeliveryNoteSignatureRequestMail;
use App\Models\DeliveryNote;
use App\Models\DownloadRequest;
use App\Models\Person;
use App\Models\Project;
use App\Models\SignatureRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use ZsgsDesign\PDFConverter\Latex;

class DeliveryNoteController extends Controller
{
    protected function resourceAbilityMap()
    {
        return array_merge(parent::resourceAbilityMap(), [
            'showEmail' => 'email',
            'email' => 'email',
            'download' => 'createPdf',
            'showEmailSignatureRequest' => 'emailSignatureRequest',
            'emailSignatureRequest' => 'emailSignatureRequest',
            'showEmailDownloadRequest' => 'emailDownloadRequest',
            'emailDownloadRequest' => 'emailDownloadRequest',
            'showSignatureRequest' => 'sign',
            'sign' => 'sign',
            'approve' => 'approve',
        ]);
    }

    public function __construct()
    {
        $this->authorizeResource(DeliveryNote::class, 'delivery_note');
    }

    public function index(Request $request)
    {
        DeliveryNote::handleDefaultFilter($request);

        $deliveryNotes = DeliveryNote::filterSearch($request->search)
            ->order($request->sort)
            ->with('project')
            ->with('employee.person')
            ->with('activities.causer')
            ->paginate(Auth::user()->settings->list_pagination_size)
            ->appends($request->except('page'));

        return view('delivery_note.index')->with(compact('deliveryNotes'));
    }

    public function create(DeliveryNoteCreateRequest $request)
    {
        $deliveryNote = null;

        $validatedData = $request->validated();

        if (isset($validatedData['project'])) {
            $currentProject = Project::find($validatedData['project']);
        }

        $projects = Project::order()->get();

        return view('delivery_note.create')
            ->with('deliveryNote', null)
            ->with('currentProject', null)
            ->with('projects', $projects->toJson());
    }

    public function store(DeliveryNoteStoreRequest $request)
    {
        $validatedData = $request->validated();

        $deliveryNote = DeliveryNote::make($validatedData);
        $deliveryNote->employee_id = Auth::user()->employee_id;
        $deliveryNote->status = 'new';

        $deliveryNote->save();

        $deliveryNote->addDocument($request->document);

        if ($request->send_signature_request) {
            return redirect()
                ->route('delivery-notes.email-signature-request', $deliveryNote)
                ->with('success', 'Der Lieferschein wurde erfolgreich angelegt.');
        } else {
            return redirect()
                ->route('delivery-notes.show', $deliveryNote)
                ->with('success', 'Der Lieferschein wurde erfolgreich angelegt.');
        }
    }

    public function show(DeliveryNote $deliveryNote)
    {
        $deliveryNote
            ->load('employee.person')
            ->load('media')
            ->load('activities.causer');

        return view('delivery_note.show')
            ->with(compact('deliveryNote'));
    }

    public function edit(DeliveryNote $deliveryNote)
    {
        $currentProject = $deliveryNote->project;
        $projects = Project::order()->get();

        return view('delivery_note.edit')
            ->with('deliveryNote', $deliveryNote)
            ->with('currentProject', $currentProject)
            ->with('projects', $projects->toJson());
    }

    public function update(DeliveryNoteUpdateRequest $request, DeliveryNote $deliveryNote)
    {
        $validatedData = $request->validated();

        $deliveryNote->update($validatedData);

        if ($deliveryNote->status === 'signed') {
            $deliveryNote->status = 'new';
            $deliveryNote->save();

            $deliveryNote->deleteDownloadRequest();
            $deliveryNote->deleteSignature();
        }

        if($request->document) {
            $deliveryNote->deleteDocument();
            $deliveryNote->addDocument($request->document);

            $deliveryNote->touch();
        }

        $deliveryNote->deleteSignatureRequest();

        if ($request->send_signature_request) {
            return redirect()
                ->route('delivery-notes.email-signature-request', $deliveryNote)
                ->with('success', 'Der Lieferschein wurde erfolgreich bearbeitet.');
        } else {
            return redirect()
                ->route('delivery-notes.show', $deliveryNote)
                ->with('success', 'Der Lieferschein wurde erfolgreich bearbeitet.');
        }
    }

    public function destroy(Request $request, DeliveryNote $deliveryNote)
    {
        $deliveryNote->deleteDownloadRequest();

        $deliveryNote->deleteSignatureRequest();
        $deliveryNote->deleteSignature();

        $deliveryNote->deleteDocument();
        $deliveryNote->activities()->delete();
        $deliveryNote->delete();

        return $this->getConditionalRedirect($request->redirect, $deliveryNote)
            ->with('success', 'Der Lieferschein wurde erfolgreich entfernt.');
    }

    public function showEmail(DeliveryNote $deliveryNote)
    {
        $deliveryNote
            ->load('project')
            ->load('media');

        $people = Person::whereNotNull('email')->order()->get();

        return view('delivery_note.email')
            ->with('deliveryNote', $deliveryNote)
            ->with('people', $people->toJson())
            ->with('currentTo', null)
            ->with('currentCC', null)
            ->with('currentBCC', null);
    }

    public function email(EmailRequest $request, DeliveryNote $deliveryNote)
    {
        $validatedData = $request->validated();

        $attachments = $request->attachment_ids ? Media::find($request->attachment_ids) : null;

        $deliveryNote
            ->load('project')
            ->load('employee.person');

        $mail = Mail::to($request->email_to);
        if ($request->email_cc) {
            $mail = $mail->cc($request->email_cc);
        }
        if ($request->email_bcc) {
            $mail = $mail->bcc($request->email_bcc);
        }

        $mail->send(new DeliveryNoteMail($deliveryNote, $attachments));

        return $this->getConditionalRedirect($request->redirect, $deliveryNote)
            ->with('success', 'Der Lieferschein wurde erfolgreich gesendet.');
    }

    public function showEmailSignatureRequest(DeliveryNote $deliveryNote)
    {
        $deliveryNote
            ->load('project.company.contactPerson');

        return view('delivery_note.email_signature_request', $deliveryNote)
            ->with(compact('deliveryNote'));
    }

    public function emailSignatureRequest(SingleEmailRequest $request, DeliveryNote $deliveryNote)
    {
        $validatedData = $request->validated();

        $deliveryNote->generateSignatureRequest();

        $deliveryNote
            ->load('employee.person')
            ->load('project')
            ->load('signatureRequest');

        Mail::to($request->email)->send(new DeliveryNoteSignatureRequestMail($deliveryNote));

        return $this->getConditionalRedirect($request->redirect, $deliveryNote)
            ->with('success', 'Die Anfrage zur Unterschrift wurde erfolgreich gesendet.');
    }

    public function showSignatureRequest(DeliveryNote $deliveryNote)
    {
        $deliveryNote
            ->load('project.company.contactPerson');

        return view('delivery_note.show_signature_request')->with(compact('deliveryNote'));
    }

    public function customerShowSignatureRequest(Request $request, string $token)
    {
        $deliveryNote = SignatureRequest::fromToken(DeliveryNote::class, $token);

        if ($deliveryNote) {
            $deliveryNote->generateDownloadRequest();

            $deliveryNote
                ->load('project')
                ->load('employee.person')
                ->load('signatureRequest')
                ->load('downloadRequest');
        } else {
            $request->session()->flash('warning', 'Kein Lieferschein zum Unterschreiben und Herunterladen vorhanden.');
        }

        return view('delivery_note.show_customer_signature_request')->with(compact('deliveryNote'));
    }

    public function sign(SignRequest $request, DeliveryNote $deliveryNote)
    {
        $validatedData = $request->validated();

        $this->addSignature($deliveryNote, $request->signature);

        if ($request->send_download_request) {
            return redirect()
                ->route('delivery-notes.email-download-request', ['delivery_note' => $deliveryNote, 'redirect' => $request->redirect])
                ->with('success', 'Der Lieferschein wurde erfolgreich unterschrieben.');
        } else {
            return $this->getConditionalRedirect($request->redirect, $deliveryNote)
                ->with('success', 'Der Lieferschein wurde erfolgreich unterschrieben.');
        }
    }

    public function customerSign(SignRequest $request, string $token)
    {
        $validatedData = $request->validated();

        $deliveryNote = SignatureRequest::fromToken(DeliveryNote::class, $token);

        if ($deliveryNote) {
            $this->addSignature($deliveryNote, $request->signature);

            $deliveryNote->generateDownloadRequest();
            $deliveryNote->load('downloadRequest')->load('project.company.contactPerson');

            $request->session()->flash('success', 'Der Lieferschein wurde erfolgreich unterschrieben.');

            return view('delivery_note.sign_approve')->with(compact('deliveryNote'));
        } else {
            $request->session()->flash('warning', 'Kein Lieferschein zum Unterschreiben vorhanden.');

            return view('delivery_note.show_customer_signature_request')->with('deliveryNote', null);
        }
    }

    public function showEmailDownloadRequest(DeliveryNote $deliveryNote)
    {
        $deliveryNote
            ->load('project.company.contactPerson');

        return view('delivery_note.email_download_request', $deliveryNote)
            ->with(compact('deliveryNote'));
    }

    public function emailDownloadRequest(SingleEmailRequest $request, DeliveryNote $deliveryNote)
    {
        $validatedData = $request->validated();

        $deliveryNote->generateDownloadRequest();

        $this->sendDownloadRequest($deliveryNote, $request->email);

        return $this->getConditionalRedirect($request->redirect, $deliveryNote)
            ->with('success', 'Der Link zum Herunterladen wurde erfolgreich gesendet.');
    }

    public function customerEmailDownloadRequest(SingleEmailRequest $request, string $token)
    {
        $validatedData = $request->validated();

        $deliveryNote = DownloadRequest::fromToken(DeliveryNote::class, $token);

        if ($deliveryNote) {
            $deliveryNote->generateDownloadRequest();
            $this->sendDownloadRequest($deliveryNote, $request->email);

            $request->session()->flash('success', 'Der Link zum Herunterladen wurde erfolgreich gesendet.');

            return view('delivery_note.sign_approve')->with(compact('deliveryNote'));
        } else {
            $request->session()->flash('warning', 'Kein Lieferschein zum Herunterladen vorhanden.');

            return view('delivery_note.download_invalid');
        }
    }

    public function download(DeliveryNote $deliveryNote)
    {
        return $this->downloadPDF($deliveryNote);
    }

    public function customerDownload(Request $request, string $token)
    {
        $deliveryNote = DownloadRequest::fromToken(DeliveryNote::class, $token);

        if ($deliveryNote) {
            $deliveryNote->deleteDownloadRequest();

            return $this->downloadPDF($deliveryNote);
        } else {
            $request->session()->flash('warning', 'Kein Lieferschein zum Herunterladen vorhanden.');

            return view('delivery_note.download_invalid');
        }
    }

    public function finish(Request $request, DeliveryNote $deliveryNote)
    {
        $deliveryNote->update(['status' => 'finished']);

        return $this->getConditionalRedirect($request->redirect, $deliveryNote)
            ->with('success', 'Der Lieferschein wurde erfolgreich erledigt.');
    }

    private function sendDownloadRequest(DeliveryNote $deliveryNote, string $email)
    {
        $deliveryNote
            ->load('employee.person')
            ->load('project')
            ->load('downloadRequest');

        Mail::to($email)->send(new DeliveryNoteDownloadRequestMail($deliveryNote));
    }

    private function downloadPDF(DeliveryNote $deliveryNote)
    {
        if(!$deliveryNote->signature()) {
            return $deliveryNote->document();
        }

        $deliveryNote->load('media');

        $title = str_replace(array('\\','/',':','*','?','"','<','>','|'),'_', $deliveryNote->title);

        $filename = "LI $title.pdf";

        return (new Latex())
            ->binPath('/usr/bin/pdflatex')
            ->untilAuxSettles()
            ->view('latex.delivery_note', [
                'deliveryNote' => $deliveryNote,
            ])
            ->download($filename);
    }

    private function addSignature(DeliveryNote $deliveryNote, string $signature)
    {
        $deliveryNote->addSignature($signature);

        $deliveryNote->status = 'signed';
        $deliveryNote->save();

        $deliveryNote->deleteSignatureRequest();

        event(new DeliveryNoteSignedEvent($deliveryNote));
    }

    private function getConditionalRedirect(?string $target, DeliveryNote $deliveryNote)
    {
        switch ($target) {
            case 'project':
                $route = 'projects.show';
                $parameters = ['project' => $deliveryNote->project, 'tab' => 'delivery_notes'];
                break;
            case 'show':
                $route = 'delivery-notes.show';
                $parameters = ['delivery_note' => $deliveryNote];
                break;
            default:
                $route = 'delivery-notes.index';
                $parameters = [];
                break;
        }

        return redirect()->route($route, $parameters);
    }
}
