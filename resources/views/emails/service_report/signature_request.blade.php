@component('mail::message')
Guten Tag,

es liegt ein neuer Servicebericht von MTS Management Technik Systeme GmbH & CO KG zur Unterschrift vor.
Klicken Sie bitte auf den folgenden Button um den Servicebericht herunterzuladen und zu unterschreiben.

@include("emails.service_report.report_content")

@component('mail::button', ['url' => route('service-reports.sign', $serviceReport->signatureRequest->token)])
Servicebericht herunterladen und unterschreiben
@endcomponent

Danke,<br />
{{ $serviceReport->employee->person->name }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
