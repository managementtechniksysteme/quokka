@component('mail::message')
Guten Tag,

es liegt ein neuer Bautagesbericht von MTS Management Technik Systeme GmbH & CO KG zum Herunterladen vor.
Klicken Sie bitte auf den folgenden Button, um den Bautagesbericht herunterzuladen.

@include("emails.construction_report.construction_report_content_request")

@component('mail::button', ['url' => route('construction-reports.customer-download', $constructionReport->downloadRequest->token)])
    Bautagesbericht herunterladen
@endcomponent

Danke,<br />
{{ $constructionReport->employee->person->name }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
