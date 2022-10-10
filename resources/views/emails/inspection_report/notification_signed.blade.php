@component('mail::message')
Hallo,

der Prüfbericht Anlage {{ $inspectionReport->equipment_identifier }}
(Kunde {{ $inspectionReport->project->company->name }}) vom {{ $inspectionReport->inspected_on }} wurde unterschrieben.

**Durchgeführte Arbeiten und Bemerkungen**<br />
{{ $inspectionReport->comment }}

@component('mail::button', ['url' => route('inspection-reports.show', $inspectionReport)])
    Prüfbericht in {{ config('app.name') }} öffnen
@endcomponent

Danke,<br />
{{ config('app.name') }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
