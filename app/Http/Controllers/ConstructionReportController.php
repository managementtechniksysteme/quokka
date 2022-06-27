<?php

namespace App\Http\Controllers;

use App\Events\ConstructionReportCreatedEvent;
use App\Events\ConstructionReportSignedEvent;
use App\Events\ConstructionReportUpdatedEvent;
use App\Http\Requests\ConstructionReportCreateRequest;
use App\Http\Requests\ConstructionReportStoreRequest;
use App\Http\Requests\ConstructionReportUpdateRequest;
use App\Http\Requests\EmailRequest;
use App\Http\Requests\SignRequest;
use App\Http\Requests\SingleEmailRequest;
use App\Mail\ConstructionReportDownloadRequestMail;
use App\Mail\ConstructionReportMail;
use App\Mail\ConstructionReportSignatureRequestMail;
use App\Models\ApplicationSettings;
use App\Models\ConstructionReport;
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

class ConstructionReportController extends Controller
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
        $this->authorizeResource(ConstructionReport::class, 'construction_report');
    }

    public function index(Request $request)
    {
        ConstructionReport::handleDefaultFilter($request);

        $constructionReports = ConstructionReport::filterPermissions()
            ->filterSearch($request->search)
            ->order($request->sort)
            ->with('project')
            ->with('employee.person')
            ->with('activities.causer')
            ->paginate(Auth::user()->settings->list_pagination_size)
            ->appends($request->except('page'));

        return view('construction_report.index')->with(compact('constructionReports'));
    }

    public function create(ConstructionReportCreateRequest $request)
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

        return view('construction_report.create')
            ->with('constructionReport', null)
            ->with('currentProject', $currentProject)
            ->with('projects', $projects->toJson())
            ->with('currentInvolvedEmployees', $currentInvolvedEmployees->toJson())
            ->with('employees', $employees->toJson())
            ->with('currentPresentPeople', null)
            ->with('people', $people->toJson())
            ->with('currentAttachments', null)
            ->with('minAccountingAmount', $minAccountingAmount);
    }

    public function store(ConstructionReportStoreRequest $request)
    {
        $validatedData = $request->validated();

        $constructionReport = ConstructionReport::make($validatedData);
        $constructionReport->employee_id = Auth::user()->employee_id;
        $constructionReport->status = 'new';
        $constructionReport->number = 1;

        $latestConstructionReport = ConstructionReport::where('project_id', $request->project_id)->latest('number')->first();

        if ($latestConstructionReport) {
            $constructionReport->number = $latestConstructionReport->number + 1;
        }

        $constructionReport->save();

        $constructionReport->involvedEmployees()->attach(Employee::find($request->involved_ids), ['employee_type' => 'involved']);

        if ($request->filled('present_ids')) {
            $constructionReport->presentPeople()->attach(Person::find($request->present_ids), ['person_type' => 'present']);
        }

        if ($request->new_attachments) {
            $constructionReport->addAttachments($request->new_attachments);
        }

        event(new ConstructionReportCreatedEvent($constructionReport, Auth::user(), Auth::user()->settings->notify_self));

        if ($request->send_signature_request) {
            return redirect()
                ->route('construction-reports.email-signature-request', $constructionReport)
                ->with('success', 'Der Regiebericht wurde erfolgreich angelegt.');
        } else {
            return redirect()
                ->route('construction-reports.show', $constructionReport)
                ->with('success', 'Der Regiebericht wurde erfolgreich angelegt.');
        }
    }

    public function show(ConstructionReport $constructionReport)
    {
        $constructionReport
            ->load('employee.person')
            ->load(['involvedEmployees.person' => function ($query) {
                $query->order();
            }])
            ->load(['presentPeople' => function ($query) {
                $query->order();
            }])
            ->load('media')
            ->load('activities.causer');

        return view('construction_report.show')
            ->with(compact('constructionReport'));
    }

    public function edit(ConstructionReport $constructionReport)
    {
        $currentProject = $constructionReport->project;
        $projects = Project::order()->get();

        $currentInvolvedEmployees = Person::order()->find($constructionReport->involvedEmployees->pluck('person_id'));
        $employees = Person::has('employee')->order()->get();

        $currentPresentPeople = $constructionReport->presentPeople ?? null;
        $people = Person::order()->get();

        $currentAttachments = $constructionReport->attachmentsWithUrl();

        $minAccountingAmount = ApplicationSettings::get()->accounting_min_amount;

        return view('construction_report.edit')
            ->with('constructionReport', $constructionReport)
            ->with('currentProject', $currentProject)
            ->with('projects', $projects->toJson())
            ->with('currentInvolvedEmployees', $currentInvolvedEmployees)
            ->with('employees', $employees->toJson())
            ->with('currentPresentPeople', $currentPresentPeople)
            ->with('people', $people->toJson())
            ->with('currentAttachments', $currentAttachments->toJson())
            ->with('minAccountingAmount', $minAccountingAmount);
    }

    public function update(ConstructionReportUpdateRequest $request, ConstructionReport $constructionReport)
    {
        $validatedData = $request->validated();

        $constructionReport->update($validatedData);

        if ($constructionReport->status === 'signed') {
            $constructionReport->status = 'new';
            $constructionReport->save();

            $constructionReport->deleteDownloadRequest();
            $constructionReport->deleteSignature();
        }

        $touched = $constructionReport->involvedEmployees()->syncWithPivotValues($request->involved_ids, ['employee_type' => 'involved']);

        if($touched['attached'] || $touched['detached'] || $touched['updated']) {
            $constructionReport->touch();
        }

        if ($request->filled('present_ids')) {
            $touched = $constructionReport->presentPeople()->syncWithPivotValues($request->present_ids, ['person_type' => 'present']);

            if($touched['attached'] || $touched['detached'] || $touched['updated']) {
                $constructionReport->touch();
            }
        } else {
            $touched = $constructionReport->presentPeople()->detach();

            if($touched > 0) {
                $constructionReport->touch();
            }
        }

        if ($request->remove_attachments) {
            $constructionReport->deleteAttachments($request->remove_attachments);

            $constructionReport->touch();
        }

        if ($request->new_attachments) {
            $constructionReport->addAttachments($request->new_attachments);

            $constructionReport->touch();
        }

        $constructionReport->deleteSignatureRequest();

        if($constructionReport->wasChanged()) {
            event(new ConstructionReportUpdatedEvent($constructionReport, Auth::user(), Auth::user()->settings->notify_self));
        }

        if ($request->send_signature_request) {
            return redirect()
                ->route('construction-reports.email-signature-request', $constructionReport)
                ->with('success', 'Der Regiebericht wurde erfolgreich bearbeitet.');
        } else {
            return redirect()
                ->route('construction-reports.show', $constructionReport)
                ->with('success', 'Der Regiebericht wurde erfolgreich bearbeitet.');
        }
    }

    public function destroy(Request $request, ConstructionReport $constructionReport)
    {
        $constructionReport->deleteDownloadRequest();

        $constructionReport->deleteSignatureRequest();
        $constructionReport->deleteSignature();

        $constructionReport->deleteAttachments();
        $constructionReport->delete();

        return $this->getConditionalRedirect($request->redirect, $constructionReport)
            ->with('success', 'Der Regiebericht wurde erfolgreich entfernt.');
    }

    public function showEmail(ConstructionReport $constructionReport)
    {
        $constructionReport
            ->load('project')
            ->load('media');

        $currentTo = $constructionReport->presentPeople()->order()->get();
        $people = Person::whereNotNull('email')->order()->get();

        return view('construction_report.email')
            ->with('constructionReport', $constructionReport)
            ->with('people', $people->toJson())
            ->with('currentTo', $currentTo->toJson())
            ->with('currentCC', null)
            ->with('currentBCC', null);
    }

    public function email(EmailRequest $request, ConstructionReport $constructionReport)
    {
        $validatedData = $request->validated();

        $attachments = $request->attachment_ids ? Media::find($request->attachment_ids) : null;

        $constructionReport
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

        $mail->send(new ConstructionReportMail($constructionReport, $attachments));

        return $this->getConditionalRedirect($request->redirect, $constructionReport)
            ->with('success', 'Der Regiebericht wurde erfolgreich gesendet.');
    }

    public function showEmailSignatureRequest(ConstructionReport $constructionReport)
    {
        $constructionReport
            ->load('project.company.contactPerson');

        return view('construction_report.email_signature_request', $constructionReport)
            ->with(compact('constructionReport'));
    }

    public function emailSignatureRequest(SingleEmailRequest $request, ConstructionReport $constructionReport)
    {
        $validatedData = $request->validated();

        $constructionReport->generateSignatureRequest();

        $constructionReport
            ->load('employee.person')
            ->load(['involvedEmployees.person' => function ($query) {
                $query->order();
            }])
            ->load(['presentPeople' => function ($query) {
                $query->order();
            }])
            ->load('signatureRequest');

        Mail::to($request->email)->send(new ConstructionReportSignatureRequestMail($constructionReport));

        return $this->getConditionalRedirect($request->redirect, $constructionReport)
            ->with('success', 'Die Anfrage zur Unterschrift wurde erfolgreich gesendet.');
    }

    public function showSignatureRequest(ConstructionReport $constructionReport)
    {
        $constructionReport
            ->load('project.company.contactPerson');

        return view('construction_report.show_signature_request')->with(compact('constructionReport'));
    }

    public function customerShowSignatureRequest(Request $request, string $token)
    {
        $constructionReport = SignatureRequest::fromToken(ConstructionReport::class, $token);

        if ($constructionReport) {
            $constructionReport
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

        return view('construction_report.show_customer_signature_request')->with(compact('constructionReport'));
    }

    public function sign(SignRequest $request, ConstructionReport $constructionReport)
    {
        $validatedData = $request->validated();

        $this->addSignature($constructionReport, $request->signature);

        if ($request->send_download_request) {
            return redirect()
                ->route('construction-reports.email-download-request', ['construction_report' => $constructionReport, 'redirect' => $request->redirect])
                ->with('success', 'Der Regiebericht wurde erfolgreich unterschrieben.');
        } else {
            return $this->getConditionalRedirect($request->redirect, $constructionReport)
                ->with('success', 'Der Regiebericht wurde erfolgreich unterschrieben.');
        }
    }

    public function customerSign(SignRequest $request, string $token)
    {
        $validatedData = $request->validated();

        $constructionReport = SignatureRequest::fromToken(ConstructionReport::class, $token);

        if ($constructionReport) {
            $this->addSignature($constructionReport, $request->signature);

            $constructionReport->generateDownloadRequest();
            $constructionReport->load('downloadRequest')->load('project.company.contactPerson');

            $request->session()->flash('success', 'Der Regiebericht wurde erfolgreich unterschrieben.');

            return view('construction_report.sign_approve')->with(compact('constructionReport'));
        } else {
            $request->session()->flash('warning', 'Kein Regiebericht zum Unterschreiben vorhanden.');

            return view('construction_report.show_customer_signature_request')->with('constructionReport', null);
        }
    }

    public function showEmailDownloadRequest(ConstructionReport $constructionReport)
    {
        $constructionReport
            ->load('project.company.contactPerson');

        return view('construction_report.email_download_request', $constructionReport)
            ->with(compact('constructionReport'));
    }

    public function emailDownloadRequest(SingleEmailRequest $request, ConstructionReport $constructionReport)
    {
        $validatedData = $request->validated();

        $constructionReport->generateDownloadRequest();

        $this->sendDownloadRequest($constructionReport, $request->email);

        return $this->getConditionalRedirect($request->redirect, $constructionReport)
            ->with('success', 'Der Link zum Herunterladen wurde erfolgreich gesendet.');
    }

    public function customerEmailDownloadRequest(SingleEmailRequest $request, string $token)
    {
        $validatedData = $request->validated();

        $constructionReport = DownloadRequest::fromToken(ConstructionReport::class, $token);

        if ($constructionReport) {
            $constructionReport->generateDownloadRequest();
            $this->sendDownloadRequest($constructionReport, $request->email);

            $request->session()->flash('success', 'Der Link zum Herunterladen wurde erfolgreich gesendet.');

            return view('construction_report.sign_approve')->with(compact('constructionReport'));
        } else {
            $request->session()->flash('warning', 'Kein Regiebericht zum Herunterladen vorhanden.');

            return view('construction_report.download_invalid');
        }
    }

    public function download(ConstructionReport $constructionReport)
    {
        return $this->downloadPDF($constructionReport);
    }

    public function customerDownload(Request $request, string $token)
    {
        $constructionReport = DownloadRequest::fromToken(ConstructionReport::class, $token);

        if ($constructionReport) {
            $constructionReport->deleteDownloadRequest();

            return $this->downloadPDF($constructionReport);
        } else {
            $request->session()->flash('warning', 'Kein Regiebericht zum Herunterladen vorhanden.');

            return view('construction_report.download_invalid');
        }
    }

    public function finish(Request $request, ConstructionReport $constructionReport)
    {
        $constructionReport->update(['status' => 'finished']);

        return $this->getConditionalRedirect($request->redirect, $constructionReport)
            ->with('success', 'Der Regiebericht wurde erfolgreich erledigt.');
    }

    private function sendDownloadRequest(ConstructionReport $constructionReport, string $email)
    {
        $constructionReport
            ->load('employee.person')
            ->load(['involvedEmployees.person' => function ($query) {
                $query->order();
            }])
            ->load(['presentPeople' => function ($query) {
                $query->order();
            }])
            ->load('downloadRequest');

        Mail::to($email)->send(new ConstructionReportDownloadRequestMail($constructionReport));
    }

    private function downloadPDF(ConstructionReport $constructionReport)
    {
        $constructionReport
            ->load('project')
            ->load('employee.person')
            ->load(['involvedEmployees.person' => function ($query) {
                $query->order();
            }])
            ->load(['presentPeople' => function ($query) {
                $query->order();
            }])
            ->load('activities.causer');

        return (new Latex())
            ->binPath('/usr/bin/pdflatex')
            ->untilAuxSettles()
            ->view('latex.construction_report', ['constructionReport' => $constructionReport])
            ->download('BT '.$constructionReport->project->name.' #'.$constructionReport->number.'.pdf');
    }

    private function addSignature(ConstructionReport $constructionReport, string $signature)
    {
        $constructionReport->addSignature($signature);

        $constructionReport->status = 'signed';
        $constructionReport->save();

        $constructionReport->deleteSignatureRequest();

        event(new ConstructionReportSignedEvent($constructionReport));
    }

    private function getConditionalRedirect(?string $target, ConstructionReport $constructionReport)
    {
        switch ($target) {
            case 'project':
                $route = 'projects.show';
                $parameters = ['project' => $constructionReport->project, 'tab' => 'construction_reports'];
                break;
            case 'show':
                $route = 'construction-reports.show';
                $parameters = ['construction_report' => $constructionReport];
                break;
            default:
                $route = 'construction-reports.index';
                $parameters = [];
                break;
        }

        return redirect()->route($route, $parameters);
    }
}
