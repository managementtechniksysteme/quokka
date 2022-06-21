@component('mail::message')
Hallo,

der Regiebericht Projekt {{ $additionsReport->project->name }} #{{ $additionsReport->number }}
({{ $additionsReport->services_provided_on }}), an dem du beteiligt bist, wurde
@if($isNew) angelegt @else bearbeitet @endif .

**Leistungsfortschritt**<br />
{{ $additionsReport->comment }}

@component('mail::button', ['url' => route('additions-reports.show', $additionsReport)])
    Regiebericht in {{ config('app.name') }} öffnen
@endcomponent

Danke,<br />
{{ config('app.name') }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
