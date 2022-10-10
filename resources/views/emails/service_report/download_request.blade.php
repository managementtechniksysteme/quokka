@component('mail::message')
Guten Tag,

es liegt ein neuer Servicebericht von MTS Management Technik Systeme GmbH & CO KG zum Herunterladen vor.
Klicken Sie bitte auf den folgenden Button, um den Servicebericht herunterzuladen.

@include("emails.service_report.service_report_content_request")

@component('mail::button', ['url' => route('service-reports.customer-download', $serviceReport->downloadRequest->token)])
    Servicebericht herunterladen
@endcomponent

Danke,<br />
{{ $serviceReport->employee->person->name }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
