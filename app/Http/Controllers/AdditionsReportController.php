<?php

namespace App\Http\Controllers;

use App\Events\AdditionsReportCreatedEvent;
use App\Events\AdditionsReportSignedEvent;
use App\Events\AdditionsReportUpdatedEvent;
use App\Http\Requests\AdditionsReportCreateRequest;
use App\Http\Requests\AdditionsReportStoreRequest;
use App\Http\Requests\AdditionsReportUpdateRequest;
use App\Http\Requests\EmailRequest;
use App\Http\Requests\SignRequest;
use App\Http\Requests\SingleEmailRequest;
use App\Mail\AdditionsReportDownloadRequestMail;
use App\Mail\AdditionsReportMail;
use App\Mail\AdditionsReportSignatureRequestMail;
use App\Models\AdditionsReport;
use App\Models\ApplicationSettings;
use App\Models\DownloadRequest;
use App\Models\Employee;
use App\Models\Person;
use App\Models\Project;
use App\Models\SignatureRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use ZsgsDesign\PDFConverter\Latex;

class AdditionsReportController extends Controller
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
        $this->authorizeResource(AdditionsReport::class, 'additions_report');
    }

    public function index(Request $request)
    {
        AdditionsReport::handleDefaultFilter($request);

        $additionsReports = AdditionsReport::filterPermissions()
            ->filterSearch($request->search)
            ->order($request->sort)
            ->with('project')
            ->with('employee.person')
            ->paginate(Auth::user()->settings->list_pagination_size)
            ->appends($request->except('page'));

        return view('additions_report.index')->with(compact('additionsReports'));
    }

    public function create(AdditionsReportCreateRequest $request)
    {
        $currentProject = null;

        $validatedData = $request->validated();

        if (isset($validatedData['project'])) {
            $currentProject = Project::find($validatedData['project']);
        }

        $projects = Project::order()->get();

        $currentInvolvedEmployees = collect([Auth::user()->employee->person]);
        $employees = Person::has('employee')->order()->get();

        $people = Person::order()->get();

        $minAccountingAmount = ApplicationSettings::get()->accounting_min_amount;

        return view('additions_report.create')
            ->with('additionsReport', null)
            ->with('currentProject', $currentProject)
            ->with('projects', $projects->toJson())
            ->with('currentInvolvedEmployees', $currentInvolvedEmployees->toJson())
            ->with('employees', $employees->toJson())
            ->with('currentPresentPeople', null)
            ->with('people', $people->toJson())
            ->with('currentAttachments', null)
            ->with('minAccountingAmount', $minAccountingAmount);
    }

    public function store(AdditionsReportStoreRequest $request)
    {
        $validatedData = $request->validated();

        $additionsReport = AdditionsReport::make($validatedData);
        $additionsReport->employee_id = Auth::user()->employee_id;
        $additionsReport->status = 'new';
        $additionsReport->number = 1;

        $latestAdditionsReport = AdditionsReport::where('project_id', $request->project_id)->latest('number')->first();

        if ($latestAdditionsReport) {
            $additionsReport->number = $latestAdditionsReport->number + 1;
        }

        $additionsReport->save();

        $additionsReport->involvedEmployees()->attach(Employee::find($request->involved_ids), ['employee_type' => 'involved']);

        if ($request->filled('present_ids')) {
            $additionsReport->presentPeople()->attach(Person::find($request->present_ids), ['person_type' => 'present']);
        }

        if ($request->new_attachments) {
            $additionsReport->addAttachments($request->new_attachments);
        }

        event(new AdditionsReportCreatedEvent($additionsReport));

        if ($request->send_signature_request) {
            return redirect()
                ->route('additions-reports.email-signature-request', $additionsReport)
                ->with('success', 'Der Regiebericht wurde erfolgreich angelegt.');
        } else {
            return redirect()
                ->route('additions-reports.show', $additionsReport)
                ->with('success', 'Der Regiebericht wurde erfolgreich angelegt.');
        }
    }

    public function show(AdditionsReport $additionsReport)
    {
        $additionsReport
            ->load('employee.person')
            ->load(['involvedEmployees.person' => function ($query) {
                $query->order();
            }])
            ->load(['presentPeople' => function ($query) {
                $query->order();
            }])
            ->load('media');

        return view('additions_report.show')
            ->with(compact('additionsReport'));
    }

    public function edit(AdditionsReport $additionsReport)
    {
        $currentProject = $additionsReport->project;
        $projects = Project::order()->get();

        $currentInvolvedEmployees = Person::order()->find($additionsReport->involvedEmployees->pluck('person_id'));
        $employees = Person::has('employee')->order()->get();

        $currentPresentPeople = $additionsReport->presentPeople ?? null;
        $people = Person::order()->get();

        $currentAttachments = $additionsReport->attachmentsWithUrl();

        $minAccountingAmount = ApplicationSettings::get()->accounting_min_amount;

        return view('additions_report.edit')
            ->with('additionsReport', $additionsReport)
            ->with('currentProject', $currentProject)
            ->with('projects', $projects->toJson())
            ->with('currentInvolvedEmployees', $currentInvolvedEmployees)
            ->with('employees', $employees->toJson())
            ->with('currentPresentPeople', $currentPresentPeople)
            ->with('people', $people->toJson())
            ->with('currentAttachments', $currentAttachments->toJson())
            ->with('minAccountingAmount', $minAccountingAmount);
    }

    public function update(AdditionsReportUpdateRequest $request, AdditionsReport $additionsReport)
    {
        $validatedData = $request->validated();

        $additionsReport->update($validatedData);

        if ($additionsReport->status === 'signed') {
            $additionsReport->status = 'new';
            $additionsReport->save();

            $additionsReport->deleteDownloadRequest();
            $additionsReport->deleteSignature();
        }

        $touched = $additionsReport->involvedEmployees()->syncWithPivotValues($request->involved_ids, ['employee_type' => 'involved']);

        if($touched['attached'] || $touched['detached'] || $touched['updated']) {
            $additionsReport->touch();
        }

        if ($request->filled('present_ids')) {
            $touched = $additionsReport->presentPeople()->syncWithPivotValues($request->present_ids, ['person_type' => 'present']);

            if($touched['attached'] || $touched['detached'] || $touched['updated']) {
                $additionsReport->touch();
            }
        } else {
            $touched = $additionsReport->presentPeople()->detach();

            if($touched > 0) {
                $additionsReport->touch();
            }
        }

        if ($request->remove_attachments) {
            $additionsReport->deleteAttachments($request->remove_attachments);

            $additionsReport->touch();
        }

        if ($request->new_attachments) {
            $additionsReport->addAttachments($request->new_attachments);

            $additionsReport->touch();
        }

        $additionsReport->deleteSignatureRequest();

        if($additionsReport->wasChanged()) {
            event(new AdditionsReportUpdatedEvent($additionsReport));
        }

        if ($request->send_signature_request) {
            return redirect()
                ->route('additions-reports.email-signature-request', $additionsReport)
                ->with('success', 'Der Regiebericht wurde erfolgreich bearbeitet.');
        } else {
            return redirect()
                ->route('additions-reports.show', $additionsReport)
                ->with('success', 'Der Regiebericht wurde erfolgreich bearbeitet.');
        }
    }

    public function destroy(Request $request, AdditionsReport $additionsReport)
    {
        $additionsReport->deleteDownloadRequest();

        $additionsReport->deleteSignatureRequest();
        $additionsReport->deleteSignature();

        $additionsReport->deleteAttachments();
        $additionsReport->delete();

        return $this->getConditionalRedirect($request->redirect, $additionsReport)
            ->with('success', 'Der Regiebericht wurde erfolgreich entfernt.');
    }

    public function showEmail(AdditionsReport $additionsReport)
    {
        $additionsReport
            ->load('project')
            ->load('media');

        $currentTo = $additionsReport->presentPeople()->order()->get();
        $people = Person::whereNotNull('email')->order()->get();

        return view('additions_report.email')
            ->with('additionsReport', $additionsReport)
            ->with('people', $people->toJson())
            ->with('currentTo', $currentTo->toJson())
            ->with('currentCC', null)
            ->with('currentBCC', null);
    }

    public function email(EmailRequest $request, AdditionsReport $additionsReport)
    {
        $validatedData = $request->validated();

        $attachments = $request->attachment_ids ? Media::find($request->attachment_ids) : null;

        $additionsReport
            ->load('project')
            ->load('employee.person')
            ->load(['involvedEmployees.person' => function ($query) {
                $query->order();
            }])
            ->load(['presentPeople' => function ($query) {
                $query->order();
            }]);

        $mail = Mail::to($request->email_to);
        if ($request->email_cc) {
            $mail = $mail->cc($request->email_cc);
        }
        if ($request->email_bcc) {
            $mail = $mail->bcc($request->email_bcc);
        }

        $mail->send(new AdditionsReportMail($additionsReport, $attachments));

        return $this->getConditionalRedirect($request->redirect, $additionsReport)
            ->with('success', 'Der Regiebericht wurde erfolgreich gesendet.');
    }

    public function showEmailSignatureRequest(AdditionsReport $additionsReport)
    {
        $additionsReport
            ->load('project.company.contactPerson');

        return view('additions_report.email_signature_request', $additionsReport)
            ->with(compact('additionsReport'));
    }

    public function emailSignatureRequest(SingleEmailRequest $request, AdditionsReport $additionsReport)
    {
        $validatedData = $request->validated();

        $additionsReport->generateSignatureRequest();

        $additionsReport
            ->load('employee.person')
            ->load(['involvedEmployees.person' => function ($query) {
                $query->order();
            }])
            ->load(['presentPeople' => function ($query) {
                $query->order();
            }])
            ->load('signatureRequest');

        Mail::to($request->email)->send(new AdditionsReportSignatureRequestMail($additionsReport));

        return $this->getConditionalRedirect($request->redirect, $additionsReport)
            ->with('success', 'Die Anfrage zur Unterschrift wurde erfolgreich gesendet.');
    }

    public function showSignatureRequest(AdditionsReport $additionsReport)
    {
        $additionsReport
            ->load('project.company.contactPerson');

        return view('additions_report.show_signature_request')->with(compact('additionsReport'));
    }

    public function customerShowSignatureRequest(Request $request, string $token)
    {
        $additionsReport = SignatureRequest::fromToken(AdditionsReport::class, $token);

        if ($additionsReport) {
            $additionsReport
                ->load('project')
                ->load('employee.person')
                ->load(['involvedEmployees.person' => function ($query) {
                    $query->order();
                }])
                ->load(['presentPeople' => function ($query) {
                    $query->order();
                }])
                ->load('signatureRequest');
        } else {
            $request->session()->flash('warning', 'Kein Regiebericht zum Unterschreiben und Herunterladen vorhanden.');
        }

        return view('additions_report.show_customer_signature_request')->with(compact('additionsReport'));
    }

    public function sign(SignRequest $request, AdditionsReport $additionsReport)
    {
        $validatedData = $request->validated();

        $this->addSignature($additionsReport, $request->signature);

        if ($request->send_download_request) {
            return redirect()
                ->route('additions-reports.email-download-request', ['additions_report' => $additionsReport, 'redirect' => $request->redirect])
                ->with('success', 'Der Regiebericht wurde erfolgreich unterschrieben.');
        } else {
            return $this->getConditionalRedirect($request->redirect, $additionsReport)
                ->with('success', 'Der Regiebericht wurde erfolgreich unterschrieben.');
        }
    }

    public function customerSign(SignRequest $request, string $token)
    {
        $validatedData = $request->validated();

        $additionsReport = SignatureRequest::fromToken(AdditionsReport::class, $token);

        if ($additionsReport) {
            $this->addSignature($additionsReport, $request->signature);

            $additionsReport->generateDownloadRequest();
            $additionsReport->load('downloadRequest')->load('project.company.contactPerson');

            $request->session()->flash('success', 'Der Regiebericht wurde erfolgreich unterschrieben.');

            return view('additions_report.sign_approve')->with(compact('additionsReport'));
        } else {
            $request->session()->flash('warning', 'Kein Regiebericht zum Unterschreiben vorhanden.');

            return view('additions_report.show_customer_signature_request')->with('additionsReport', null);
        }
    }

    public function showEmailDownloadRequest(AdditionsReport $additionsReport)
    {
        $additionsReport
            ->load('project.company.contactPerson');

        return view('additions_report.email_download_request', $additionsReport)
            ->with(compact('additionsReport'));
    }

    public function emailDownloadRequest(SingleEmailRequest $request, AdditionsReport $additionsReport)
    {
        $validatedData = $request->validated();

        $additionsReport->generateDownloadRequest();

        $this->sendDownloadRequest($additionsReport, $request->email);

        return $this->getConditionalRedirect($request->redirect, $additionsReport)
            ->with('success', 'Der Link zum Herunterladen wurde erfolgreich gesendet.');
    }

    public function customerEmailDownloadRequest(SingleEmailRequest $request, string $token)
    {
        $validatedData = $request->validated();

        $additionsReport = DownloadRequest::fromToken(AdditionsReport::class, $token);

        if ($additionsReport) {
            $additionsReport->generateDownloadRequest();
            $this->sendDownloadRequest($additionsReport, $request->email);

            $request->session()->flash('success', 'Der Link zum Herunterladen wurde erfolgreich gesendet.');

            return view('additions_report.sign_approve')->with(compact('additionsReport'));
        } else {
            $request->session()->flash('warning', 'Kein Regiebericht zum Herunterladen vorhanden.');

            return view('additions_report.download_invalid');
        }
    }

    public function download(AdditionsReport $additionsReport)
    {
        return $this->downloadPDF($additionsReport);
    }

    public function customerDownload(Request $request, string $token)
    {
        $additionsReport = DownloadRequest::fromToken(AdditionsReport::class, $token);

        if ($additionsReport) {
            $additionsReport->deleteDownloadRequest();

            return $this->downloadPDF($additionsReport);
        } else {
            $request->session()->flash('warning', 'Kein Regiebericht zum Herunterladen vorhanden.');

            return view('additions_report.download_invalid');
        }
    }

    public function finish(Request $request, AdditionsReport $additionsReport)
    {
        $additionsReport->update(['status' => 'finished']);

        return $this->getConditionalRedirect($request->redirect, $additionsReport)
            ->with('success', 'Der Regiebericht wurde erfolgreich erledigt.');
    }

    private function sendDownloadRequest(AdditionsReport $additionsReport, string $email)
    {
        $additionsReport
            ->load('employee.person')
            ->load(['involvedEmployees.person' => function ($query) {
                $query->order();
            }])
            ->load(['presentPeople' => function ($query) {
                $query->order();
            }])
            ->load('downloadRequest');

        Mail::to($email)->send(new AdditionsReportDownloadRequestMail($additionsReport));
    }

    private function downloadPDF(AdditionsReport $additionsReport)
    {
        $additionsReport
            ->load('project')
            ->load('employee.person')
            ->load(['involvedEmployees.person' => function ($query) {
                $query->order();
            }])
            ->load(['presentPeople' => function ($query) {
                $query->order();
            }]);

        return (new Latex())
            ->binPath('/usr/bin/pdflatex')
            ->untilAuxSettles()
            ->view('latex.additions_report', ['additionsReport' => $additionsReport])
            ->download('RB '.$additionsReport->project->name.' #'.$additionsReport->number.'.pdf');
    }

    private function addSignature(AdditionsReport $additionsReport, string $signature)
    {
        $additionsReport->addSignature($signature);

        $additionsReport->status = 'signed';
        $additionsReport->save();

        $additionsReport->deleteSignatureRequest();

        event(new AdditionsReportSignedEvent($additionsReport));
    }

    private function getConditionalRedirect(?string $target, AdditionsReport $additionsReport)
    {
        switch ($target) {
            case 'project':
                $route = 'projects.show';
                $parameters = ['project' => $additionsReport->project, 'tab' => 'additions_reports'];
                break;
            case 'show':
                $route = 'additions-reports.show';
                $parameters = ['additions_report' => $additionsReport];
                break;
            default:
                $route = 'additions-reports.index';
                $parameters = [];
                break;
        }

        return redirect()->route($route, $parameters);
    }

}
