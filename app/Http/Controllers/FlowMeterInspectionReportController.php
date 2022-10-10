<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Http\Requests\FlowMeterInspectionReportCreateRequest;
use App\Http\Requests\FlowMeterInspectionReportStoreRequest;
use App\Events\FlowMeterInspectionReportCreatedEvent;
use App\Events\FlowMeterInspectionReportSignedEvent;
use App\Events\FlowMeterInspectionReportUpdatedEvent;
use App\Http\Requests\FlowMeterInspectionReportUpdateRequest;
use App\Http\Requests\SignRequest;
use App\Http\Requests\SingleEmailRequest;
use App\Mail\FlowMeterInspectionReportDownloadRequestMail;
use App\Mail\FlowMeterInspectionReportMail;
use App\Mail\FlowMeterInspectionReportSignatureRequestMail;
use App\Models\ApplicationSettings;
use App\Models\DownloadRequest;
use App\Models\FlowMeterInspectionReport;
use App\Models\FlowMeterInspectionReportMeasurements;
use App\Models\Person;
use App\Models\Project;
use App\Models\SignatureRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use ZsgsDesign\PDFConverter\Latex;

class FlowMeterInspectionReportController extends Controller
{
    private $COMPARISON_MEASUREMENT_Q_PERCENTAGES= [20, 30, 50, 70, 100];

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
        $this->authorizeResource(FlowMeterInspectionReport::class, 'flow_meter_inspection_report');
    }

    public function index(Request $request)
    {
        FlowMeterInspectionReport::handleDefaultFilter($request);

        $flowMeterInspectionReports = FlowMeterInspectionReport::filterPermissions()
            ->filterSearch($request->search)
            ->order($request->sort)
            ->with('project')
            ->with('employee.person')
            ->with('activities.causer')
            ->paginate(Auth::user()->settings->list_pagination_size)
            ->appends($request->except('page'));

        return view('flow_meter_inspection_report.index')->with(compact('flowMeterInspectionReports'));
    }

    public function create(FlowMeterInspectionReportCreateRequest $request)
    {
        $templateFlowMeterInspectionReport = null;
        $currentProject = null;

        $validatedData = $request->validated();

        if(isset($validatedData['template'])) {
            $templateFlowMeterInspectionReport = FlowMeterInspectionReport::find($validatedData['template']);

            if(!$templateFlowMeterInspectionReport) {
                return redirect()
                    ->route('flow-meter-inspection-reports.create')
                    ->with('warning', 'Der angegebene Prüfbericht existiert nicht.');
            }

            if(Auth::user()->cannot('view', $templateFlowMeterInspectionReport)) {
                return redirect()
                    ->route('flow-meter-inspection-reports.create')
                    ->with('danger', 'Du kannst diesen Prüfbericht nicht kopieren.');
            }

            $templateFlowMeterInspectionReport->load('project');

            $currentProject = $templateFlowMeterInspectionReport->project;

            $templateFlowMeterInspectionReport = $templateFlowMeterInspectionReport->replicate();
        }
        else if (isset($validatedData['project'])) {
            $currentProject = Project::find($validatedData['project']);
        }

        $projects = Project::order()->get();

        return view('flow_meter_inspection_report.create')
            ->with('flowMeterInspectionReport', $templateFlowMeterInspectionReport)
            ->with('comparison_measurement_q_percentages', $this->COMPARISON_MEASUREMENT_Q_PERCENTAGES)
            ->with('currentProject', $currentProject)
            ->with('projects', $projects->toJson())
            ->with('currentAttachments', null);
    }

    public function store(FlowMeterInspectionReportStoreRequest $request)
    {
        $validatedData = $request->validated();

        $flowMeterInspectionReport = FlowMeterInspectionReport::make($validatedData);
        $flowMeterInspectionReport->employee_id = Auth::user()->employee_id;
        $flowMeterInspectionReport->status = 'new';

        $this->unsetUnnecessaryComparisonMeasurementsFields($flowMeterInspectionReport);

        $flowMeterInspectionReport->save();

        $this->syncComparisonMeasurements($flowMeterInspectionReport, $validatedData['measurements']);

        if ($request->appendix) {
            $flowMeterInspectionReport->addAppendix($request->appendix);
        }

        if ($request->new_attachments) {
            $flowMeterInspectionReport->addAttachments($request->new_attachments);
        }

        event(new FlowMeterInspectionReportCreatedEvent($flowMeterInspectionReport, Auth::user(), Auth::user()->settings->notify_self));

        if ($request->send_signature_request) {
            return redirect()
                ->route('flow-meter-inspection-reports.email-signature-request', $flowMeterInspectionReport)
                ->with('success', 'Der Prüfbericht wurde erfolgreich angelegt.');
        } else {
            return redirect()
                ->route('flow-meter-inspection-reports.show', $flowMeterInspectionReport)
                ->with('success', 'Der Prüfbericht wurde erfolgreich angelegt.');
        }
    }

    public function show(FlowMeterInspectionReport $flowMeterInspectionReport)
    {
        $flowMeterInspectionReport
            ->load('project.company')
            ->load('employee.person')
            ->load('measurements')
            ->load('media')
            ->load('activities.causer');

        return view('flow_meter_inspection_report.show')
            ->with(compact('flowMeterInspectionReport'));
    }

    public function edit(FlowMeterInspectionReport $flowMeterInspectionReport)
    {
        $currentProject = $flowMeterInspectionReport->project;
        $projects = Project::order()->get();

        $currentAttachments = $flowMeterInspectionReport->attachmentsWithUrl();

        return view('flow_meter_inspection_report.edit')
            ->with('flowMeterInspectionReport', $flowMeterInspectionReport)
            ->with('comparison_measurement_q_percentages', $this->COMPARISON_MEASUREMENT_Q_PERCENTAGES)
            ->with('measurements')
            ->with('currentProject', $currentProject)
            ->with('projects', $projects->toJson())
            ->with('currentAttachments', $currentAttachments->toJson());
    }

    public function update(FlowMeterInspectionReportUpdateRequest $request, FlowMeterInspectionReport $flowMeterInspectionReport)
    {
        $validatedData = $request->validated();

        $flowMeterInspectionReport->update($validatedData);

        $this->unsetUnnecessaryComparisonMeasurementsFields($flowMeterInspectionReport);

        $flowMeterInspectionReport->save();

        if ($flowMeterInspectionReport->status === 'signed') {
            $flowMeterInspectionReport->status = 'new';
            $flowMeterInspectionReport->save();

            $flowMeterInspectionReport->deleteDownloadRequest();
            $flowMeterInspectionReport->deleteSignature();
        }

        $this->syncComparisonMeasurements($flowMeterInspectionReport, $validatedData['measurements']);

        if($request->appendix) {
            $flowMeterInspectionReport->deleteAppendix();
            $flowMeterInspectionReport->addAppendix($request->appendix);

            $flowMeterInspectionReport->touch();
        }

        if ($request->remove_attachments) {
            $flowMeterInspectionReport->deleteAttachments($request->remove_attachments);

            $flowMeterInspectionReport->touch();
        }

        if ($request->new_attachments) {
            $flowMeterInspectionReport->addAttachments($request->new_attachments);

            $flowMeterInspectionReport->touch();
        }

        $flowMeterInspectionReport->deleteSignatureRequest();

        if($flowMeterInspectionReport->wasChanged()) {
            event(new FlowMeterInspectionReportUpdatedEvent($flowMeterInspectionReport, Auth::user(), Auth::user()->settings->notify_self));
        }

        if ($request->send_signature_request) {
            return redirect()
                ->route('flow-meter-inspection-reports.email-signature-request', $flowMeterInspectionReport)
                ->with('success', 'Der Prüfbericht wurde erfolgreich bearbeitet.');
        } else {
            return redirect()
                ->route('flow-meter-inspection-reports.show', $flowMeterInspectionReport)
                ->with('success', 'Der Prüfbericht wurde erfolgreich bearbeitet.');
        }
    }

    public function destroy(Request $request, FlowMeterInspectionReport $flowMeterInspectionReport)
    {
        $flowMeterInspectionReport->deleteDownloadRequest();

        $flowMeterInspectionReport->deleteSignatureRequest();
        $flowMeterInspectionReport->deleteSignature();

        $flowMeterInspectionReport->deleteAppendix();
        $flowMeterInspectionReport->deleteAttachments();
        $flowMeterInspectionReport->activities()->delete();
        $flowMeterInspectionReport->delete();

        return $this->getConditionalRedirect($request->redirect, $flowMeterInspectionReport)
            ->with('success', 'Der Prüfbericht wurde erfolgreich entfernt.');
    }

    public function showEmail(FlowMeterInspectionReport $flowMeterInspectionReport)
    {
        $flowMeterInspectionReport
            ->load('project')
            ->load('measurements')
            ->load('media');

        $people = Person::whereNotNull('email')->order()->get();

        return view('flow_meter_inspection_report.email')
            ->with('flowMeterInspectionReport', $flowMeterInspectionReport)
            ->with('people', $people->toJson())
            ->with('currentTo', null)
            ->with('currentCC', null)
            ->with('currentBCC', null);
    }

    public function email(EmailRequest $request, FlowMeterInspectionReport $flowMeterInspectionReport)
    {
        $validatedData = $request->validated();

        $attachments = $request->attachment_ids ? Media::find($request->attachment_ids) : null;

        $flowMeterInspectionReport
            ->load('project.company')
            ->load('employee.person')
            ->load('measurements');

        $mail = Mail::to($request->email_to);
        if ($request->email_cc) {
            $mail = $mail->cc($request->email_cc);
        }
        if ($request->email_bcc) {
            $mail = $mail->bcc($request->email_bcc);
        }

        $mail->send(new FlowMeterInspectionReportMail($flowMeterInspectionReport, $attachments));

        return $this->getConditionalRedirect($request->redirect, $flowMeterInspectionReport)
            ->with('success', 'Der Prüfbericht wurde erfolgreich gesendet.');
    }

    public function showEmailSignatureRequest(FlowMeterInspectionReport $flowMeterInspectionReport)
    {
        $flowMeterInspectionReport
            ->load('project.company.contactPerson');

        return view('flow_meter_inspection_report.email_signature_request', $flowMeterInspectionReport)
            ->with(compact('flowMeterInspectionReport'));
    }

    public function emailSignatureRequest(SingleEmailRequest $request, FlowMeterInspectionReport $flowMeterInspectionReport)
    {
        $validatedData = $request->validated();

        $flowMeterInspectionReport->generateSignatureRequest();

        $flowMeterInspectionReport
            ->load('employee.person')
            ->load('signatureRequest');

        Mail::to($request->email)->send(new FlowMeterInspectionReportSignatureRequestMail($flowMeterInspectionReport));

        return $this->getConditionalRedirect($request->redirect, $flowMeterInspectionReport)
            ->with('success', 'Die Anfrage zur Unterschrift wurde erfolgreich gesendet.');
    }

    public function showSignatureRequest(FlowMeterInspectionReport $flowMeterInspectionReport)
    {
        $flowMeterInspectionReport
            ->load('project.company.contactPerson');

        return view('flow_meter_inspection_report.show_signature_request')->with(compact('flowMeterInspectionReport'));
    }

    public function customerShowSignatureRequest(Request $request, string $token)
    {
        $flowMeterInspectionReport = SignatureRequest::fromToken(FlowMeterInspectionReport::class, $token);

        if ($flowMeterInspectionReport) {
            $flowMeterInspectionReport
                ->load('employee.person')
                ->load('signatureRequest');
        } else {
            $request->session()->flash('warning', 'Kein Prüfbericht zum Unterschreiben und Herunterladen vorhanden.');
        }

        return view('flow_meter_inspection_report.show_customer_signature_request')
            ->with(compact('flowMeterInspectionReport'));
    }

    public function sign(SignRequest $request, FlowMeterInspectionReport $flowMeterInspectionReport)
    {
        $validatedData = $request->validated();

        $this->addSignature($flowMeterInspectionReport, $request->signature);

        if ($request->send_download_request) {
            return redirect()
                ->route('flow-meter-inspection-reports.email-download-request', ['flow_meter_inspection_report' => $flowMeterInspectionReport, 'redirect' => $request->redirect])
                ->with('success', 'Der Prüfbericht wurde erfolgreich unterschrieben.');
        } else {
            return $this->getConditionalRedirect($request->redirect, $flowMeterInspectionReport)
                ->with('success', 'Der Prüfbericht wurde erfolgreich unterschrieben.');
        }
    }

    public function customerSign(SignRequest $request, string $token)
    {
        $validatedData = $request->validated();

        $flowMeterInspectionReport = SignatureRequest::fromToken(FlowMeterInspectionReport::class, $token);

        if ($flowMeterInspectionReport) {
            $this->addSignature($flowMeterInspectionReport, $request->signature);

            $flowMeterInspectionReport->generateDownloadRequest();
            $flowMeterInspectionReport->load('downloadRequest')->load('project.company.contactPerson');

            $request->session()->flash('success', 'Der Prüfbericht wurde erfolgreich unterschrieben.');

            return view('flow_meter_inspection_report.sign_approve')->with(compact('flowMeterInspectionReport'));
        } else {
            $request->session()->flash('warning', 'Kein Prüfbericht zum Unterschreiben vorhanden.');

            return view('inspection_report.show_customer_signature_request')->with('flowMeterInspectionReport', null);
        }
    }

    public function showEmailDownloadRequest(FlowMeterInspectionReport $flowMeterInspectionReport)
    {
        $flowMeterInspectionReport
            ->load('project.company.contactPerson');

        return view('flow_meter_inspection_report.email_download_request', $flowMeterInspectionReport)
            ->with(compact('flowMeterInspectionReport'));
    }

    public function emailDownloadRequest(SingleEmailRequest $request, FlowMeterInspectionReport $flowMeterInspectionReport)
    {
        $validatedData = $request->validated();

        $flowMeterInspectionReport->generateDownloadRequest();

        $this->sendDownloadRequest($flowMeterInspectionReport, $request->email);

        return $this->getConditionalRedirect($request->redirect, $flowMeterInspectionReport)
            ->with('success', 'Der Link zum Herunterladen wurde erfolgreich gesendet.');
    }

    public function customerEmailDownloadRequest(SingleEmailRequest $request, string $token)
    {
        $validatedData = $request->validated();

        $flowMeterInspectionReport = DownloadRequest::fromToken(FlowMeterInspectionReport::class, $token);

        if ($flowMeterInspectionReport) {
            $flowMeterInspectionReport->generateDownloadRequest();
            $this->sendDownloadRequest($flowMeterInspectionReport, $request->email);

            $request->session()->flash('success', 'Der Link zum Herunterladen wurde erfolgreich gesendet.');

            return view('flow_meter_inspection_report.sign_approve')->with(compact('flowMeterInspectionReport'));
        } else {
            $request->session()->flash('warning', 'Kein Prüfbericht zum Herunterladen vorhanden.');

            return view('flow_meter_inspection_report.download_invalid');
        }
    }

    public function download(FlowMeterInspectionReport $flowMeterInspectionReport)
    {
        return $this->downloadPDF($flowMeterInspectionReport);
    }

    public function customerDownload(Request $request, string $token)
    {
        $flowMeterInspectionReport = DownloadRequest::fromToken(FlowMeterInspectionReport::class, $token);

        if ($flowMeterInspectionReport) {
            $flowMeterInspectionReport->deleteDownloadRequest();

            return $this->downloadPDF($flowMeterInspectionReport);
        } else {
            $request->session()->flash('warning', 'Kein Prüfbericht zum Herunterladen vorhanden.');

            return view('flow_meter_inspection_report.download_invalid');
        }
    }

    public function finish(Request $request, FlowMeterInspectionReport $flowMeterInspectionReport)
    {
        $flowMeterInspectionReport->update(['status' => 'finished']);

        return $this->getConditionalRedirect($request->redirect, $flowMeterInspectionReport)
            ->with('success', 'Der Prüfbericht wurde erfolgreich erledigt.');
    }

    private function sendDownloadRequest(FlowMeterInspectionReport $flowMeterInspectionReport, string $email)
    {
        $flowMeterInspectionReport
            ->load('employee.person')
            ->load('downloadRequest');

        Mail::to($email)->send(new FlowMeterInspectionReportDownloadRequestMail($flowMeterInspectionReport));
    }

    private function downloadPDF(FlowMeterInspectionReport $flowMeterInspectionReport)
    {
        $flowMeterInspectionReport
            ->load('project.company')
            ->load('employee.person')
            ->load('measurements')
            ->load('activities.causer');

        $company = ApplicationSettings::get()->company;

        return (new Latex())
            ->binPath('/usr/bin/pdflatex')
            ->untilAuxSettles()
            ->view('latex.flow_meter_inspection_report', ['flowMeterInspectionReport' => $flowMeterInspectionReport, 'company' => $company])
            ->download('DM '.$flowMeterInspectionReport->project->name.' - '.$flowMeterInspectionReport->measuring_point.'.pdf');
    }

    private function addSignature(FlowMeterInspectionReport $flowMeterInspectionReport, string $signature)
    {
        $flowMeterInspectionReport->addSignature($signature);

        $flowMeterInspectionReport->status = 'signed';
        $flowMeterInspectionReport->save();

        $flowMeterInspectionReport->deleteSignatureRequest();

        event(new FlowMeterInspectionReportSignedEvent($flowMeterInspectionReport));
    }

    private function unsetUnnecessaryComparisonMeasurementsFields(FlowMeterInspectionReport $flowMeterInspectionReport) {
        if($flowMeterInspectionReport->comparison_measurements_process === 'mobile_measurement_equipment') {
            $flowMeterInspectionReport->comparison_measurement_volumetric_basin = null;
            $flowMeterInspectionReport->comparison_measurement_volumetric_basin_cross_section_area = null;
            $flowMeterInspectionReport->comparison_measurement_volumetric_height_measurement_equipment = null;
        } else {
            $flowMeterInspectionReport->comparison_measurement_mobile_type = null;
            $flowMeterInspectionReport->comparison_measurement_mobile_type_other = null;
            $flowMeterInspectionReport->comparison_measurement_mobile_installation_point = null;
            $flowMeterInspectionReport->comparison_measurement_mobile_equipment_make = null;
            $flowMeterInspectionReport->comparison_measurement_mobile_equipment_type = null;
            $flowMeterInspectionReport->comparison_measurement_mobile_equipment_identifier = null;
            $flowMeterInspectionReport->comparison_measurement_mobile_equipment_maximum_speed = null;
            $flowMeterInspectionReport->comparison_measurement_mobile_equipment_maximum_speed_unit = null;
            $flowMeterInspectionReport->comparison_measurement_mobile_equipment_maximum_flow_rate = null;
            $flowMeterInspectionReport->comparison_measurement_mobile_equipment_maximum_flow_rate_unit = null;
            $flowMeterInspectionReport->comparison_measurement_mobile_equipment_q_min = null;
            $flowMeterInspectionReport->comparison_measurement_mobile_equipment_last_calibrated_on = null;
            $flowMeterInspectionReport->comparison_measurement_mobile_equipment_last_cal_provider = null;
            $flowMeterInspectionReport->comparison_measurement_mobile_equipment_last_cal_doc_identifier = null;
        }
    }

    private function syncComparisonMeasurements(FlowMeterInspectionReport $flowMeterInspectionReport, array $measurements) {
        foreach ($measurements as $key => $measurement) {
            if(!array_filter($measurement)) {
                unset($measurements[$key]);
            }
        }

        $toDelete = $flowMeterInspectionReport->measurements()->whereNotIn('q_percent', array_keys($measurements));

        if($toDelete->count()) {
            $toDelete->delete();

            $flowMeterInspectionReport->touch();
        }

        foreach ($measurements as $key => $measurement) {
            $measurements[$key]['q_percent'] = $key;
            $measurements[$key]['flow_meter_inspection_report_id'] = $flowMeterInspectionReport->id;
        }

        $changed = FlowMeterInspectionReportMeasurements::upsert($measurements, ['q_percent']);

        if($changed) {
            $flowMeterInspectionReport->touch();
        }
    }

    private function getConditionalRedirect(?string $target, FlowMeterInspectionReport $flowMeterInspectionReport)
    {
        switch ($target) {
            case 'project':
                $route = 'projects.show';
                $parameters = ['project' => $flowMeterInspectionReport->project, 'tab' => 'flow_meter_inspection_reports'];
                break;
            case 'show':
                $route = 'flow-meter-inspection-reports.show';
                $parameters = ['flow_meter_inspection_report' => $flowMeterInspectionReport];
                break;
            default:
                $route = 'flow-meter-inspection-reports.index';
                $parameters = [];
                break;
        }

        return redirect()->route($route, $parameters);
    }
}
