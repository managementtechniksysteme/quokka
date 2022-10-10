@component('mail::message')
Guten Tag,

es liegt ein neuer Prüfbericht von MTS Management Technik Systeme GmbH & CO KG zur Unterschrift vor.
Klicken Sie bitte auf den folgenden Button, um den Prüfbericht herunterzuladen und zu unterschreiben.

@include("emails.inspection_report.inspection_report_content_request")

@component('mail::button', ['url' => route('inspection-reports.customer-sign', $inspectionReport->signatureRequest->token)])
    Prüfbericht herunterladen und unterschreiben
@endcomponent

Danke,<br />
{{ $inspectionReport->employee->person->name }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
