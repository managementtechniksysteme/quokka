<?php

namespace App\Http\Controllers;

use App\Events\ServiceReportCreatedEvent;
use App\Events\ServiceReportSignedEvent;
use App\Events\ServiceReportUpdatedEvent;
use App\Http\Requests\EmailRequest;
use App\Http\Requests\ServiceReportSignRequest;
use App\Http\Requests\ServiceReportStoreRequest;
use App\Http\Requests\ServiceReportUpdateRequest;
use App\Http\Requests\SingleEmailRequest;
use App\Mail\ServiceReportDownloadRequestMail;
use App\Mail\ServiceReportMail;
use App\Mail\ServiceReportSignatureRequestMail;
use App\Models\DownloadRequest;
use App\Models\Person;
use App\Models\Project;
use App\Models\ServiceReport;
use App\Models\ServiceReportService;
use App\Models\SignatureRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use ZsgsDesign\PDFConverter\Latex;

class ServiceReportController extends Controller
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

        $serviceReports = ServiceReport::filter($request->input())
            ->order($request->input())
            ->with('project')
            ->with('employee.person')
            ->withMin('services', 'provided_on')
            ->withMax('services', 'provided_on')
            ->withSum('services', 'hours')
            ->withSum('services', 'allowances')
            ->withSum('services', 'kilometres')
            ->paginate(Auth::user()->settings->list_pagination_size)
            ->appends($request->except('page'));

        return view('service_report.index')->with(compact('serviceReports'));
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

        return view('service_report.create')
            ->with('serviceReport', null)
            ->with('currentProject', $currentProject)
            ->with('projects', $projects->toJson())
            ->with('currentServices', null)
            ->with('currentAttachments', null);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceReportStoreRequest $request)
    {
        $validatedData = $request->validated();

        $serviceReport = ServiceReport::make($validatedData);
        $serviceReport->employee_id = \Auth::user()->employee_id;
        $serviceReport->status = 'new';
        $serviceReport->number = 1;

        $latestServiceReport = ServiceReport::where('project_id', $request->project_id)->latest('number')->first();

        if ($latestServiceReport) {
            $serviceReport->number = $latestServiceReport->number + 1;
        }

        $serviceReport->save();

        foreach ($request->services as $service) {
            $serviceReportService = ServiceReportService::make($service);
            $serviceReportService->service_report_id = $serviceReport->id;
            $serviceReportService->save();
        }

        if ($request->new_attachments) {
            $serviceReport->addAttachments($request->new_attachments);
        }

        event(new ServiceReportCreatedEvent($serviceReport));

        if ($request->send_signature_request) {
            return redirect()->route('service-reports.email-signature-request', $serviceReport)->with('success', 'Der Servicebericht wurde erfolgreich angelegt.');
        } else {
            return redirect()->route('service-reports.index')->with('success', 'Der Servicebericht wurde erfolgreich angelegt.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ServiceReport  $serviceReport
     * @return \Illuminate\Http\Response
     */
    public function show(ServiceReport $serviceReport)
    {
        $serviceReport
            ->load('project')
            ->load('employee.person')
            ->load('services')
            ->load('media');

        return view('service_report.show')
            ->with(compact('serviceReport'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ServiceReport  $serviceReport
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceReport $serviceReport)
    {
        $serviceReport->load('signatureRequest');
        $currentProject = $serviceReport->project;
        $projects = Project::order()->get();
        $currentServices = $serviceReport->services;
        $currentAttachments = $serviceReport->attachmentsWithUrl();

        return view('service_report.edit')
            ->with('serviceReport', $serviceReport)
            ->with('currentProject', $currentProject)
            ->with('projects', $projects->toJson())
            ->with('currentServices', $currentServices->toJson())
            ->with('currentAttachments', $currentAttachments->toJson());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ServiceReport  $serviceReport
     * @return \Illuminate\Http\Response
     */
    public function update(ServiceReportUpdateRequest $request, ServiceReport $serviceReport)
    {
        $validatedData = $request->validated();

        $serviceReport->update($validatedData);

        $serviceReport->services()->delete();

        foreach ($request->services as $service) {
            $serviceReportService = ServiceReportService::make($service);
            $serviceReportService->service_report_id = $serviceReport->id;
            $serviceReportService->save();
        }

        if ($serviceReport->status === 'signed') {
            $serviceReport->status = 'new';
            $serviceReport->save();

            $serviceReport->deleteDownloadRequest();
            $serviceReport->deleteSignature();
        }

        if ($request->remove_attachments) {
            $serviceReport->deleteAttachments($request->remove_attachments);
        }

        if ($request->new_attachments) {
            $serviceReport->addAttachments($request->new_attachments);
        }

        $serviceReport->deleteSignatureRequest();

        event(new ServiceReportUpdatedEvent($serviceReport));

        if ($request->send_signature_request) {
            return redirect()->route('service-reports.email-signature-request', $serviceReport)->with('success', 'Der Servicebericht wurde erfolgreich bearbeitet.');
        } else {
            return redirect()->route('service-reports.index')->with('success', 'Der Servicebericht wurde erfolgreich bearbeitet.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ServiceReport  $serviceReport
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceReport $serviceReport)
    {
        $serviceReport->deleteDownloadRequest();

        $serviceReport->deleteSignatureRequest();
        $serviceReport->deleteSignature();

        $serviceReport->services()->delete();
        $serviceReport->delete();

        return redirect()->route('service-reports.index')->with('success', 'Der Servicebericht wurde erfolgreich entfernt.');
    }

    public function showEmail(Request $request, ServiceReport $serviceReport)
    {
        $serviceReport
            ->load('project')
            ->load('media');

        $people = Person::whereNotNull('email')->order()->get();

        return view('service_report.email')
            ->with('serviceReport', $serviceReport)
            ->with('people', $people->toJson())
            ->with('currentTo', null)
            ->with('currentCC', null)
            ->with('currentBCC', null);
    }

    public function email(EmailRequest $request, ServiceReport $serviceReport)
    {
        $validatedData = $request->validated();

        $attachments = $request->attachment_ids ? Media::find($request->attachment_ids) : null;

        $serviceReport
            ->load('project')
            ->load('employee.person')
            ->load('services');

        $mail = Mail::to($request->email_to);
        if ($request->email_cc) {
            $mail = $mail->cc($request->email_cc);
        }
        if ($request->email_bcc) {
            $mail = $mail->bcc($request->email_bcc);
        }

        $mail->send(new ServiceReportMail($serviceReport, $attachments));

        return redirect()->route('service-reports.index')->with('success', 'Der Servicebericht wurde erfolgreich gesendet.');
    }

    public function showEmailSignatureRequest(Request $request, ServiceReport $serviceReport)
    {
        $serviceReport
            ->load('project.company');

        return view('service_report.email_signature_request', $serviceReport)->with(compact('serviceReport'));
    }

    public function emailSignatureRequest(SingleEmailRequest $request, ServiceReport $serviceReport)
    {
        $validatedData = $request->validated();

        $serviceReport->generateSignatureRequest();

        $serviceReport
            ->load('project')
            ->load('employee.person')
            ->load('signatureRequest')
            ->loadMin('services', 'provided_on')
            ->loadMax('services', 'provided_on')
            ->loadSum('services', 'hours')
            ->loadSum('services', 'allowances')
            ->loadSum('services', 'kilometres');

        Mail::to($request->email)->send(new ServiceReportSignatureRequestMail($serviceReport));

        return redirect()->route('service-reports.index')->with('success', 'Die Anfrage zur Unterschrift wurde erfolgreich gesendet.');
    }

    public function showSignatureRequest(Request $request, ServiceReport $serviceReport)
    {
        $serviceReport
            ->load('project');

        return view('service_report.show_signature_request')->with(compact('serviceReport'));
    }

    public function customerShowSignatureRequest(Request $request, string $token)
    {
        $serviceReport = SignatureRequest::fromToken(ServiceReport::class, $request->token);

        if ($serviceReport) {
            $serviceReport->load('employee.person')->load('project')->load('services')->load('signatureRequest');
        } else {
            $request->session()->flash('warning', 'Kein Servicebericht zum Unterschreiben und Herunterladen vorhanden.');
        }

        return view('service_report.show_customer_signature_request')->with(compact('serviceReport'));
    }

    public function sign(ServiceReportSignRequest $request, ServiceReport $serviceReport)
    {
        $validatedData = $request->validated();

        $this->addSignature($serviceReport, $request->signature);

        if ($request->send_download_request) {
            return redirect()->route('service-reports.email-download-request', $serviceReport)->with('success', 'Der Servicebericht wurde erfolgreich unterschrieben.');
        } else {
            return redirect()->route('service-reports.index')->with('success', 'Der Servicebericht wurde erfolgreich unterschrieben.');
        }
    }

    public function customerSign(ServiceReportSignRequest $request, string $token)
    {
        $validatedData = $request->validated();

        $serviceReport = SignatureRequest::fromToken(ServiceReport::class, $token);

        if ($serviceReport) {
            $this->addSignature($serviceReport, $request->signature);

            $serviceReport->generateDownloadRequest();
            $serviceReport->load('downloadRequest')->load('project.company');

            $request->session()->flash('success', 'Der Servicebericht wurde erfolgreich unterschrieben.');

            return view('service_report.sign_approve')->with(compact('serviceReport'));
        } else {
            $request->session()->flash('warning', 'Kein Servicebericht zum Unterschreiben vorhanden.');

            return view('service_report.sign')->with('serviceReport', null);
        }
    }

    private function addSignature(ServiceReport $serviceReport, string $signature)
    {
        $serviceReport->addSignature($signature);

        $serviceReport->status = 'signed';
        $serviceReport->save();

        $serviceReport->deleteSignatureRequest();

        event(new ServiceReportSignedEvent($serviceReport));
    }

    public function showEmailDownloadRequest(Request $request, ServiceReport $serviceReport)
    {
        $serviceReport
            ->load('project.company');

        return view('service_report.email_download_request', $serviceReport)->with(compact('serviceReport'));
    }

    public function emailDownloadRequest(SingleEmailRequest $request, ServiceReport $serviceReport)
    {
        $validatedData = $request->validated();

        $serviceReport->generateDownloadRequest();

        $this->sendDownloadRequest($serviceReport, $request->email);

        return redirect()->route('service-reports.index')->with('success', 'Der Link zum Herunterladen wurde erfolgreich gesendet.');
    }

    public function customerEmailDownloadRequest(SingleEmailRequest $request, string $token)
    {
        $validatedData = $request->validated();

        $serviceReport = DownloadRequest::fromToken(ServiceReport::class, $token);

        if ($serviceReport) {
            $serviceReport->generateDownloadRequest();
            $this->sendDownloadRequest($serviceReport, $request->email);

            $request->session()->flash('success', 'Der Link zum Herunterladen wurde erfolgreich gesendet.');

            return view('service_report.sign_approve')->with(compact('serviceReport'));
        } else {
            $request->session()->flash('warning', 'Kein Servicebericht zum Herunterladen vorhanden.');

            return view('service_report.download_invalid');
        }
    }

    private function sendDownloadRequest(ServiceReport $serviceReport, string $email)
    {
        $serviceReport
            ->load('project')
            ->load('employee.person')
            ->load('downloadRequest')
            ->loadMin('services', 'provided_on')
            ->loadMax('services', 'provided_on')
            ->loadSum('services', 'hours')
            ->loadSum('services', 'allowances')
            ->loadSum('services', 'kilometres');

        Mail::to($email)->send(new ServiceReportDownloadRequestMail($serviceReport));
    }

    public function download(Request $request, ServiceReport $serviceReport)
    {
        return $this->downloadPDF(@$serviceReport);
    }

    public function customerDownload(Request $request, string $token)
    {
        $serviceReport = DownloadRequest::fromToken(ServiceReport::class, $token);

        if ($serviceReport) {
            $serviceReport->deleteDownloadRequest();

            return $this->downloadPDF($serviceReport);
        } else {
            $request->session()->flash('warning', 'Kein Servicebericht zum Herunterladen vorhanden.');

            return view('service_report.download_invalid');
        }
    }

    private function downloadPDF(ServiceReport $serviceReport)
    {
        $serviceReport
            ->load('employee.person')
            ->load('project.company.address')
            ->load('project.company.operatorAddress')
            ->load('services');

        return (new Latex())
            ->binPath('/usr/bin/pdflatex')
            ->untilAuxSettles()
            ->view('latex.service_report', ['serviceReport' => $serviceReport])
            ->download('SB '.$serviceReport->project->name.' #'.$serviceReport->number.'.pdf');
    }
}
