<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Http\Requests\InspectionReportStoreRequest;
use App\Events\InspectionReportCreatedEvent;
use App\Events\InspectionReportSignedEvent;
use App\Events\InspectionReportUpdatedEvent;
use App\Http\Requests\InspectionReportUpdateRequest;
use App\Http\Requests\SignRequest;
use App\Http\Requests\SingleEmailRequest;
use App\Mail\InspectionReportDownloadRequestMail;
use App\Mail\InspectionReportMail;
use App\Mail\InspectionReportSignatureRequestMail;
use App\Models\DownloadRequest;
use App\Models\InspectionReport;
use App\Models\Person;
use App\Models\Project;
use App\Models\SignatureRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use ZsgsDesign\PDFConverter\Latex;

class InspectionReportController extends Controller
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
        $this->authorizeResource(InspectionReport::class, 'inspection_report');
    }

    public function index(Request $request)
    {
        InspectionReport::handleDefaultFilter($request);

        $inspectionReports = InspectionReport::filterPermissions()
            ->filterSearch($request->search)
            ->order($request->sort)
            ->with('project')
            ->with('employee.person')
            ->paginate(Auth::user()->settings->list_pagination_size)
            ->appends($request->except('page'));

        return view('inspection_report.index')->with(compact('inspectionReports'));
    }

    public function create(Request $request)
    {
        $templateInspectionReport = null;
        $currentProject = null;

        if($request->filled('template')) {
            $templateInspectionReport = InspectionReport::find($request->template);

            if(!$templateInspectionReport) {
                return redirect()
                    ->route('inspection-reports.create')
                    ->with('warning', 'Der angegebene Prüfbericht existiert nicht.');
            }

            if(Auth::user()->cannot('view', $templateInspectionReport)) {
                return redirect()
                    ->route('inspection-reports.create')
                    ->with('danger', 'Du kannst diesen Prüfbericht nicht kopieren.');
            }

            $templateInspectionReport->load('project');

            $currentProject = $templateInspectionReport->project;
        }
        else if ($request->filled('project')) {
            $currentProject = Project::find($request->project);
        }

        $projects = Project::order()->get();

        return view('inspection_report.create')
            ->with('inspectionReport', $templateInspectionReport)
            ->with('currentProject', $currentProject)
            ->with('projects', $projects->toJson())
            ->with('currentAttachments', null);
    }

    public function store(InspectionReportStoreRequest $request)
    {
        $validatedData = $request->validated();

        $inspectionReport = InspectionReport::make($validatedData);
        $inspectionReport->employee_id = Auth::user()->employee_id;
        $inspectionReport->status = 'new';

        $inspectionReport->save();

        if ($request->new_attachments) {
            $inspectionReport->addAttachments($request->new_attachments);
        }

        event(new InspectionReportCreatedEvent($inspectionReport));

        if ($request->send_signature_request) {
            return redirect()
                ->route('inspection-reports.email-signature-request', $inspectionReport)
                ->with('success', 'Der Prüfbericht wurde erfolgreich angelegt.');
        } else {
            return redirect()
                ->route('inspection-reports.show', $inspectionReport)
                ->with('success', 'Der Prüfbericht wurde erfolgreich angelegt.');
        }
    }

    public function show(InspectionReport $inspectionReport)
    {
        $inspectionReport
            ->load('project.company')
            ->load('employee.person')
            ->load('media');

        return view('inspection_report.show')
            ->with(compact('inspectionReport'));
    }

    public function edit(InspectionReport $inspectionReport)
    {
        $currentProject = $inspectionReport->project;
        $projects = Project::order()->get();

        $currentAttachments = $inspectionReport->attachmentsWithUrl();

        return view('inspection_report.edit')
            ->with('inspectionReport', $inspectionReport)
            ->with('currentProject', $currentProject)
            ->with('projects', $projects->toJson())
            ->with('currentAttachments', $currentAttachments->toJson());
    }

    public function update(InspectionReportUpdateRequest $request, InspectionReport $inspectionReport)
    {
        $validatedData = $request->validated();

        $inspectionReport->update($validatedData);

        if ($inspectionReport->status === 'signed') {
            $inspectionReport->status = 'new';
            $inspectionReport->save();

            $inspectionReport->deleteDownloadRequest();
            $inspectionReport->deleteSignature();
        }

        if ($request->remove_attachments) {
            $inspectionReport->deleteAttachments($request->remove_attachments);
        }

        if ($request->new_attachments) {
            $inspectionReport->addAttachments($request->new_attachments);
        }

        $inspectionReport->deleteSignatureRequest();

        event(new InspectionReportUpdatedEvent($inspectionReport));

        if ($request->send_signature_request) {
            return redirect()
                ->route('inspection-reports.email-signature-request', $inspectionReport)
                ->with('success', 'Der Prüfbericht wurde erfolgreich bearbeitet.');
        } else {
            return redirect()
                ->route('inspection-reports.show', $inspectionReport)
                ->with('success', 'Der Prüfbericht wurde erfolgreich bearbeitet.');
        }
    }

    public function destroy(Request $request, InspectionReport $inspectionReport)
    {
        $inspectionReport->deleteDownloadRequest();

        $inspectionReport->deleteSignatureRequest();
        $inspectionReport->deleteSignature();

        $inspectionReport->delete();

        return $this->getConditionalRedirect($request->redirect, $inspectionReport)
            ->with('success', 'Der Prüfbericht wurde erfolgreich entfernt.');
    }

    public function showEmail(InspectionReport $inspectionReport)
    {
        $inspectionReport
            ->load('project')
            ->load('media');

        $people = Person::whereNotNull('email')->order()->get();

        return view('inspection_report.email')
            ->with('inspectionReport', $inspectionReport)
            ->with('people', $people->toJson())
            ->with('currentTo', null)
            ->with('currentCC', null)
            ->with('currentBCC', null);
    }

    public function email(EmailRequest $request, InspectionReport $inspectionReport)
    {
        $validatedData = $request->validated();

        $attachments = $request->attachment_ids ? Media::find($request->attachment_ids) : null;

        $inspectionReport
            ->load('project.company')
            ->load('employee.person');

        $mail = Mail::to($request->email_to);
        if ($request->email_cc) {
            $mail = $mail->cc($request->email_cc);
        }
        if ($request->email_bcc) {
            $mail = $mail->bcc($request->email_bcc);
        }

        $mail->send(new InspectionReportMail($inspectionReport, $attachments));

        return $this->getConditionalRedirect($request->redirect, $inspectionReport)
            ->with('success', 'Der Prüfbericht wurde erfolgreich gesendet.');
    }

    public function showEmailSignatureRequest(InspectionReport $inspectionReport)
    {
        $inspectionReport
            ->load('project.company.contactPerson');

        return view('inspection_report.email_signature_request', $inspectionReport)
            ->with(compact('inspectionReport'));
    }

    public function emailSignatureRequest(SingleEmailRequest $request, InspectionReport $inspectionReport)
    {
        $validatedData = $request->validated();

        $inspectionReport->generateSignatureRequest();

        $inspectionReport
            ->load('employee.person')
            ->load('signatureRequest');

        Mail::to($request->email)->send(new InspectionReportSignatureRequestMail($inspectionReport));

        return $this->getConditionalRedirect($request->redirect, $inspectionReport)
            ->with('success', 'Die Anfrage zur Unterschrift wurde erfolgreich gesendet.');
    }

    public function showSignatureRequest(InspectionReport $inspectionReport)
    {
        $inspectionReport
            ->load('project.company.contactPerson');

        return view('inspection_report.show_signature_request')->with(compact('inspectionReport'));
    }

    public function customerShowSignatureRequest(Request $request, string $token)
    {
        $inspectionReport = SignatureRequest::fromToken(InspectionReport::class, $token);

        if ($inspectionReport) {
            $inspectionReport
                ->load('employee.person')
                ->load('signatureRequest');
        } else {
            $request->session()->flash('warning', 'Kein Prüfbericht zum Unterschreiben und Herunterladen vorhanden.');
        }

        return view('inspection_report.show_customer_signature_request')
            ->with(compact('inspectionReport'));
    }

    public function sign(SignRequest $request, InspectionReport $inspectionReport)
    {
        $validatedData = $request->validated();

        $this->addSignature($inspectionReport, $request->signature);

        if ($request->send_download_request) {
            return redirect()
                ->route('inspection-reports.email-download-request', ['inspection_report' => $inspectionReport, 'redirect' => $request->redirect])
                ->with('success', 'Der Prüfbericht wurde erfolgreich unterschrieben.');
        } else {
            return $this->getConditionalRedirect($request->redirect, $inspectionReport)
                ->with('success', 'Der Prüfbericht wurde erfolgreich unterschrieben.');
        }
    }

    public function customerSign(SignRequest $request, string $token)
    {
        $validatedData = $request->validated();

        $inspectionReport = SignatureRequest::fromToken(InspectionReport::class, $token);

        if ($inspectionReport) {
            $this->addSignature($inspectionReport, $request->signature);

            $inspectionReport->generateDownloadRequest();
            $inspectionReport->load('downloadRequest')->load('project.company.contactPerson');

            $request->session()->flash('success', 'Der Prüfbericht wurde erfolgreich unterschrieben.');

            return view('inspection_report.sign_approve')->with(compact('inspectionReport'));
        } else {
            $request->session()->flash('warning', 'Kein Prüfbericht zum Unterschreiben vorhanden.');

            return view('inspection_report.show_customer_signature_request')->with('inspectionReport', null);
        }
    }

    public function showEmailDownloadRequest(InspectionReport $inspectionReport)
    {
        $inspectionReport
            ->load('project.company.contactPerson');

        return view('inspection_report.email_download_request', $inspectionReport)
            ->with(compact('inspectionReport'));
    }

    public function emailDownloadRequest(SingleEmailRequest $request, InspectionReport $inspectionReport)
    {
        $validatedData = $request->validated();

        $inspectionReport->generateDownloadRequest();

        $this->sendDownloadRequest($inspectionReport, $request->email);

        return $this->getConditionalRedirect($request->redirect, $inspectionReport)
            ->with('success', 'Der Link zum Herunterladen wurde erfolgreich gesendet.');
    }

    public function customerEmailDownloadRequest(SingleEmailRequest $request, string $token)
    {
        $validatedData = $request->validated();

        $inspectionReport = DownloadRequest::fromToken(InspectionReport::class, $token);

        if ($inspectionReport) {
            $inspectionReport->generateDownloadRequest();
            $this->sendDownloadRequest($inspectionReport, $request->email);

            $request->session()->flash('success', 'Der Link zum Herunterladen wurde erfolgreich gesendet.');

            return view('inspection_report.sign_approve')->with(compact('inspectionReport'));
        } else {
            $request->session()->flash('warning', 'Kein Prüfbericht zum Herunterladen vorhanden.');

            return view('inspection_report.download_invalid');
        }
    }

    public function download(InspectionReport $inspectionReport)
    {
        return $this->downloadPDF($inspectionReport);
    }

    public function customerDownload(Request $request, string $token)
    {
        $inspectionReport = DownloadRequest::fromToken(InspectionReport::class, $token);

        if ($inspectionReport) {
            $inspectionReport->deleteDownloadRequest();

            return $this->downloadPDF($inspectionReport);
        } else {
            $request->session()->flash('warning', 'Kein Prüfbericht zum Herunterladen vorhanden.');

            return view('inspection_report.download_invalid');
        }
    }

    public function finish(Request $request, InspectionReport $inspectionReport)
    {
        $inspectionReport->update(['status' => 'finished']);

        return $this->getConditionalRedirect($request->redirect, $inspectionReport)
            ->with('success', 'Der Prüfbericht wurde erfolgreich erledigt.');
    }

    private function sendDownloadRequest(InspectionReport $inspectionReport, string $email)
    {
        $inspectionReport
            ->load('employee.person')
            ->load('downloadRequest');

        Mail::to($email)->send(new InspectionReportDownloadRequestMail($inspectionReport));
    }

    private function downloadPDF(InspectionReport $inspectionReport)
    {
        $inspectionReport
            ->load('project.company')
            ->load('employee.person');

        return (new Latex())
            ->binPath('/usr/bin/pdflatex')
            ->untilAuxSettles()
            ->view('latex.inspection_report', ['inspectionReport' => $inspectionReport])
            ->download('PB '.$inspectionReport->project->name.' #'.$inspectionReport->number.'.pdf');
    }

    private function addSignature(InspectionReport $inspectionReport, string $signature)
    {
        $inspectionReport->addSignature($signature);

        $inspectionReport->status = 'signed';
        $inspectionReport->save();

        $inspectionReport->deleteSignatureRequest();

        event(new InspectionReportSignedEvent($inspectionReport));
    }

    private function getConditionalRedirect(?string $target, InspectionReport $inspectionReport)
    {
        switch ($target) {
            case 'project':
                $route = 'projects.show';
                $parameters = ['project' => $inspectionReport->project, 'tab' => 'inspection_reports'];
                break;
            case 'show':
                $route = 'inspection-reports.show';
                $parameters = ['inspection_report' => $inspectionReport];
                break;
            default:
                $route = 'inspection-reports.index';
                $parameters = [];
                break;
        }

        return redirect()->route($route, $parameters);
    }
}
