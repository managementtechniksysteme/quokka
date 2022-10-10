@component('mail::message')
Guten Tag,

es liegt ein neuer Regiebericht von MTS Management Technik Systeme GmbH & CO KG zum Herunterladen vor.
Klicken Sie bitte auf den folgenden Button, um den Regiebericht herunterzuladen.

@include("emails.additions_report.additions_report_content_request")

@component('mail::button', ['url' => route('additions-reports.customer-download', $additionsReport->downloadRequest->token)])
    Regiebericht herunterladen
@endcomponent

Danke,<br />
{{ $additionsReport->employee->person->name }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
