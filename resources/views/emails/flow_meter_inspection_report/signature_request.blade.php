@component('mail::message')
Guten Tag,

es liegt ein neuer Prüfbericht für Durchflusseinrichtungen von MTS Management Technik Systeme GmbH & CO KG zur Unterschrift vor.
Klicken Sie bitte auf den folgenden Button, um den Prüfbericht herunterzuladen und zu unterschreiben.

@include("emails.flow_meter_inspection_report.flow_meter_inspection_report_content_request")

@component('mail::button', ['url' => route('flow-meter-inspection-reports.customer-sign', $flowMeterInspectionReport->signatureRequest->token)])
    Prüfbericht herunterladen und unterschreiben
@endcomponent

Danke,<br />
{{ $flowMeterInspectionReport->employee->person->name }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
