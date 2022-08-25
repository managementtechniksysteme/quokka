<?php

namespace App\Http\Controllers;

use App\Events\ServiceReportCreatedEvent;
use App\Events\ServiceReportSignedEvent;
use App\Events\ServiceReportUpdatedEvent;
use App\Http\Requests\EmailRequest;
use App\Http\Requests\ServiceReportCreateRequest;
use App\Http\Requests\SignRequest;
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
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use ZsgsDesign\PDFConverter\Latex;

class ServiceReportController extends Controller
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
        $this->authorizeResource(ServiceReport::class, 'service_report');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        ServiceReport::handleDefaultFilter($request);

        $serviceReports = ServiceReport::filterPermissions()
            ->filterSearch($request->search)
            ->order($request->sort)
            ->with('project')
            ->with('employee.person')
            ->with('activities.causer')
            ->withMin('services', 'provided_on')
            ->withMax('services', 'provided_on')
            ->withSum('services', 'hours')
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
    public function create(ServiceReportCreateRequest $request)
    {
        $serviceReport = null;
        $comment = null;
        $currentProject = null;
        $currentServices = null;

        $validatedData = $request->validated();

        if (isset($validatedData['accounting'])) {
            try {
                [$currentProject, $currentServices, $comment] =
                    ServiceReportService::getServicesForAccounting($validatedData['accounting']);
            }
            catch (Exception $exception) {
                return back()->with('danger', $exception->getMessage());
            }
        }
        else if (isset($validatedData['logbook'])) {
            try {
                [$currentProject, $currentServices, $comment] =
                    ServiceReportService::getServicesForLogbook($validatedData['logbook']);
            }
            catch (Exception $exception) {
                return back()->with('danger', $exception->getMessage());
            }
        }
        else if (isset($validatedData['project'])) {
            $currentProject = Project::find($validatedData['project']);
        }

        if($comment) {
            $serviceReport = ServiceReport::make([
                'comment' => $comment
            ]);
        }

        $projects = Project::order()->get();

        return view('service_report.create')
            ->with('serviceReport', $serviceReport)
            ->with('currentProject', $currentProject)
            ->with('projects', $projects->toJson())
            ->with('currentServices', $currentServices)
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
        $serviceReport->employee_id = Auth::user()->employee_id;
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

        event(new ServiceReportCreatedEvent($serviceReport, Auth::user(), Auth::user()->settings->notify_self));

        if ($request->send_signature_request) {
            return redirect()
                ->route('service-reports.email-signature-request', $serviceReport)
                ->with('success', 'Der Servicebericht wurde erfolgreich angelegt.');
        } else {
            return redirect()
                ->route('service-reports.show', $serviceReport)
                ->with('success', 'Der Servicebericht wurde erfolgreich angelegt.');
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
            ->load('media')
            ->load('activities.causer');

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

        $oldProjectId = $serviceReport->project_id;
        $newProjectId = (int)$validatedData['project_id'];

        unset($validatedData['project_id']);
        $serviceReport->update($validatedData);

        if($oldProjectId !== $newProjectId) {
            $serviceReport->project_id = $newProjectId;
            $serviceReport->number = 1;

            $latestServiceReport = ServiceReport::where('project_id', $newProjectId)->latest('number')->first();

            if ($latestServiceReport) {
                $serviceReport->number = $latestServiceReport->number + 1;
            }

            $serviceReport->save();
        }

        $datesToKeep = $request->services ? array_column($request->services, 'provided_on') : [];

        $serviceReport->services()->whereNotIn('provided_on', $datesToKeep)->delete();

        foreach ($request->services as $service) {
            $savedService = $serviceReport->services()
                ->where('service_report_id', $serviceReport->id)
                ->where('provided_on', $service['provided_on'])
                ->first();

            if($savedService && ($savedService->hours !== $service['hours'] || $savedService->kilometres !== $service['kilometres'])) {
                DB::table((new ServiceReportService())->getTable())
                    ->where('service_report_id', $serviceReport->id)
                    ->where('provided_on', $service['provided_on'])
                    ->update([
                        'hours' => $service['hours'],
                        'kilometres' => $service['kilometres'],
                    ]);

                $serviceReport->touch();
            }
            else if(!$savedService) {
                ServiceReportService::create([
                    'service_report_id' => $serviceReport->id,
                    'provided_on' => $service['provided_on'],
                    'hours' => $service['hours'],
                    'kilometres' => $service['kilometres'],
                ]);
            }
        }

        if ($serviceReport->status === 'signed') {
            $serviceReport->status = 'new';
            $serviceReport->save();

            $serviceReport->deleteDownloadRequest();
            $serviceReport->deleteSignature();
        }

        if ($request->remove_attachments) {
            $serviceReport->deleteAttachments($request->remove_attachments);

            $serviceReport->touch();
        }

        if ($request->new_attachments) {
            $serviceReport->addAttachments($request->new_attachments);

            $serviceReport->touch();
        }

        $serviceReport->deleteSignatureRequest();

        if($serviceReport->wasChanged()) {
            event(new ServiceReportUpdatedEvent($serviceReport, Auth::user(), Auth::user()->settings->notify_self));
        }

        if ($request->send_signature_request) {
            return redirect()
                ->route('service-reports.email-signature-request', $serviceReport)
                ->with('success', 'Der Servicebericht wurde erfolgreich bearbeitet.');
        } else {
            return redirect()
                ->route('service-reports.show', $serviceReport)
                ->with('success', 'Der Servicebericht wurde erfolgreich bearbeitet.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ServiceReport  $serviceReport
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ServiceReport $serviceReport)
    {
        $serviceReport->deleteDownloadRequest();

        $serviceReport->deleteSignatureRequest();
        $serviceReport->deleteSignature();

        $serviceReport->services()->delete();
        $serviceReport->deleteAttachments();
        $serviceReport->activities()->delete();
        $serviceReport->delete();

        return $this->getConditionalRedirect($request->redirect, $serviceReport)
            ->with('success', 'Der Servicebericht wurde erfolgreich entfernt.');
    }

    public function showEmail(ServiceReport $serviceReport)
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

        return $this->getConditionalRedirect($request->redirect, $serviceReport)
            ->with('success', 'Der Servicebericht wurde erfolgreich gesendet.');
    }

    public function showEmailSignatureRequest(ServiceReport $serviceReport)
    {
        $serviceReport
            ->load('project.company.contactPerson');

        return view('service_report.email_signature_request', $serviceReport)
            ->with(compact('serviceReport'));
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
            ->loadSum('services', 'kilometres');

        Mail::to($request->email)->send(new ServiceReportSignatureRequestMail($serviceReport));

        return $this->getConditionalRedirect($request->redirect, $serviceReport)
            ->with('success', 'Die Anfrage zur Unterschrift wurde erfolgreich gesendet.');
    }

    public function showSignatureRequest(ServiceReport $serviceReport)
    {
        $serviceReport
            ->load('project.company.contactPerson');

        return view('service_report.show_signature_request')->with(compact('serviceReport'));
    }

    public function customerShowSignatureRequest(Request $request, string $token)
    {
        $serviceReport = SignatureRequest::fromToken(ServiceReport::class, $token);

        if ($serviceReport) {
            $serviceReport
                ->load('project')
                ->load('employee.person')
                ->load('services')
                ->load('signatureRequest');
        } else {
            $request->session()->flash('warning', 'Kein Servicebericht zum Unterschreiben und Herunterladen vorhanden.');
        }

        return view('service_report.show_customer_signature_request')->with(compact('serviceReport'));
    }

    public function sign(SignRequest $request, ServiceReport $serviceReport)
    {
        $validatedData = $request->validated();

        $this->addSignature($serviceReport, $request->signature);

        if ($request->send_download_request) {
            return redirect()
                ->route('service-reports.email-download-request', ['service_report' => $serviceReport, 'redirect' => $request->redirect])
                ->with('success', 'Der Servicebericht wurde erfolgreich unterschrieben.');
        } else {
            return $this->getConditionalRedirect($request->redirect, $serviceReport)
                ->with('success', 'Der Servicebericht wurde erfolgreich unterschrieben.');
        }
    }

    public function customerSign(SignRequest $request, string $token)
    {
        $validatedData = $request->validated();

        $serviceReport = SignatureRequest::fromToken(ServiceReport::class, $token);

        if ($serviceReport) {
            $this->addSignature($serviceReport, $request->signature);

            $serviceReport->generateDownloadRequest();
            $serviceReport->load('downloadRequest')->load('project.company.contactPerson');

            $request->session()->flash('success', 'Der Servicebericht wurde erfolgreich unterschrieben.');

            return view('service_report.sign_approve')->with(compact('serviceReport'));
        } else {
            $request->session()->flash('warning', 'Kein Servicebericht zum Unterschreiben vorhanden.');

            return view('service_report.show_customer_signature_request')->with('serviceReport', null);
        }
    }

    public function showEmailDownloadRequest(ServiceReport $serviceReport)
    {
        $serviceReport
            ->load('project.company.contactPerson');

        return view('service_report.email_download_request', $serviceReport)
            ->with(compact('serviceReport'));
    }

    public function emailDownloadRequest(SingleEmailRequest $request, ServiceReport $serviceReport)
    {
        $validatedData = $request->validated();

        $serviceReport->generateDownloadRequest();

        $this->sendDownloadRequest($serviceReport, $request->email);

        return $this->getConditionalRedirect($request->redirect, $serviceReport)
            ->with('success', 'Der Link zum Herunterladen wurde erfolgreich gesendet.');
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

    public function download(ServiceReport $serviceReport)
    {
        return $this->downloadPDF($serviceReport);
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

    public function finish(Request $request, ServiceReport $serviceReport)
    {
        $serviceReport->update(['status' => 'finished']);

        return $this->getConditionalRedirect($request->redirect, $serviceReport)
            ->with('success', 'Der Servicebericht wurde erfolgreich erledigt.');
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
            ->loadSum('services', 'kilometres');

        Mail::to($email)->send(new ServiceReportDownloadRequestMail($serviceReport));
    }

    private function downloadPDF(ServiceReport $serviceReport)
    {
        $serviceReport
            ->load('project')
            ->load('employee.person')
            ->load('project.company.address')
            ->load('project.company.operatorAddress')
            ->load('services')
            ->load('activities.causer');

        return (new Latex())
            ->binPath('/usr/bin/pdflatex')
            ->untilAuxSettles()
            ->view('latex.service_report', ['serviceReport' => $serviceReport])
            ->download('SB '.$serviceReport->project->name.' #'.$serviceReport->number.'.pdf');
    }

    private function addSignature(ServiceReport $serviceReport, string $signature)
    {
        $serviceReport->addSignature($signature);

        $serviceReport->status = 'signed';
        $serviceReport->save();

        $serviceReport->deleteSignatureRequest();

        event(new ServiceReportSignedEvent($serviceReport));
    }

    private function getConditionalRedirect(?string $target, ServiceReport $serviceReport)
    {
        switch ($target) {
            case 'project':
                $route = 'projects.show';
                $parameters = ['project' => $serviceReport->project, 'tab' => 'service_reports'];
                break;
            case 'show':
                $route = 'service-reports.show';
                $parameters = ['service_report' => $serviceReport];
                break;
            default:
                $route = 'service-reports.index';
                $parameters = [];
                break;
        }

        return redirect()->route($route, $parameters);
    }
}
