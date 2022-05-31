@component('mail::message')
Hallo,

du wurdest im Bautagesbericht Projekt {{ $constructionReport->project->name }} #{{ $constructionReport->number }}
({{ $constructionReport->services_provided_on }}) erwähnt.

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
