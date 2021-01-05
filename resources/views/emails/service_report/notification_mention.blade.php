@component('mail::message')
Hallo,

du wurdest im Servicebericht Projekt {{ $serviceReport->project->name }} #{{ $serviceReport->number }} erwähnt.

**Kurzbericht**<br />
{{ $serviceReport->comment }}

@component('mail::button', ['url' => route('service-reports.show', $serviceReport)])
    Servicebericht in {{ config('app.name') }} öffnen
@endcomponent

Danke,<br />
{{ config('app.name') }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
