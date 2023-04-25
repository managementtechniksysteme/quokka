@component('mail::message')
Hallo,

der Lieferschein {{ $deliveryNote->title }} (Projekt {{ $deliveryNote->project->name }}) wurde unterschrieben.

@if($deliveryNote->comment)
**Bemerkungen**<br />
{{ $deliveryNote->comment }}
@endif

@component('mail::button', ['url' => route('delivery-notes.show', $deliveryNote)])
    Lieferschein in {{ config('app.name') }} öffnen
@endcomponent

Danke,<br />
{{ config('app.name') }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
