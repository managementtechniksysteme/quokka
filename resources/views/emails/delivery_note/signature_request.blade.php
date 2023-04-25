@component('mail::message')
Guten Tag,

es liegt ein neuer Lieferschein von MTS Management Technik Systeme GmbH & CO KG zur Unterschrift vor.
Klicken Sie bitte auf den folgenden Button, um den Lierferschein herunterzuladen und zu unterschreiben.

@include("emails.delivery_note.delivery_note_content_request")

@component('mail::button', ['url' => route('delivery-notes.customer-sign', $deliveryNote->signatureRequest->token)])
    Lieferscein herunterladen und unterschreiben
@endcomponent

Danke,<br />
{{ $deliveryNote->employee->person->name }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
