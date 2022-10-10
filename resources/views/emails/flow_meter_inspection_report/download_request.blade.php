@component('mail::message')
Guten Tag,

es liegt ein neuer Prüfbericht für Durchflusseinrichtungen von MTS Management Technik Systeme GmbH & CO KG zum Herunterladen vor.
Klicken Sie bitte auf den folgenden Button, um den Prüfbericht herunterzuladen.

@include("emails.flow_meter_inspection_report.flow_meter_inspection_report_content_request")

@component('mail::button', ['url' => route('flow-meter-inspection-reports.customer-download', $flowMeterInspectionReport->downloadRequest->token)])
    Prüfbericht herunterladen
@endcomponent

Danke,<br />
{{ $flowMeterInspectionReport->employee->person->name }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
