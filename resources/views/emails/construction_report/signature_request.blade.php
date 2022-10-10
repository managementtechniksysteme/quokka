@component('mail::message')
Guten Tag,

es liegt ein neuer Bautagesbericht von MTS Management Technik Systeme GmbH & CO KG zur Unterschrift vor.
Klicken Sie bitte auf den folgenden Button, um den Bautagesbericht herunterzuladen und zu unterschreiben.

@include("emails.construction_report.construction_report_content_request")

@component('mail::button', ['url' => route('construction-reports.customer-sign', $constructionReport->signatureRequest->token)])
    Bautagesbericht herunterladen und unterschreiben
@endcomponent

Danke,<br />
{{ $constructionReport->employee->person->name }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
