@component('mail::message')
Hallo,

du wurdest im Prüfbericht für Durchflussmesseinrichtungen des Kunden {{ $flowMeterInspectionReport->project->company->name }}
vom {{ $flowMeterInspectionReport->inspected_on }}
(Anlage: {{ $flowMeterInspectionReport->equipment_identifier }}) erwähnt.

@component('mail::button', ['url' => route('flow-meter-inspection-reports.show', $flowMeterInspectionReport)])
    Prüfbericht in {{ config('app.name') }} öffnen
@endcomponent

Danke,<br />
{{ config('app.name') }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
