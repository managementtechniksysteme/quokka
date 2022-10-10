@component('mail::message')
Hallo,

der Prüfbericht für Durchflusseinrichtungen Anlage {{ $flowMeterInspectionReport->equipment_identifier }}
(Kunde {{ $flowMeterInspectionReport->project->company->name }}) vom {{ $flowMeterInspectionReport->inspected_on }} wurde unterschrieben.

@component('mail::button', ['url' => route('flow-meter-inspection-reports.show', $flowMeterInspectionReport)])
    Prüfbericht in {{ config('app.name') }} öffnen
@endcomponent

Danke,<br />
{{ config('app.name') }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
