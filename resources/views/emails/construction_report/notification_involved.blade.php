@component('mail::message')
Hallo,

der Bautagesbericht Projekt {{ $constructionReport->project->name }} #{{ $constructionReport->number }}
({{ $constructionReport->services_provided_on }}), an dem du beteiligt bist, wurde
@if($isNew) angelegt @else bearbeitet @endif .

**Leistungsfortschritt**<br />
{{ $constructionReport->comment }}

@component('mail::button', ['url' => route('construction-reports.show', $constructionReport)])
    Bautagesbericht in {{ config('app.name') }} öffnen
@endcomponent

Danke,<br />
{{ config('app.name') }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
