# Zusammenfassung des Serviceberichtes

**Techniker:** {{ $serviceReport->employee->person->name }}<br />
**Projekt:** {{ $serviceReport->project->name }} #{{ $serviceReport->number }}<br />
**Zeitraum der erbrachten Leistungen:** {{ \Carbon\Carbon::parse($serviceReport->services_min_provided_on) }} @if(\Carbon\Carbon::parse($serviceReport->services_min_provided_on)->ne(\Carbon\Carbon::parse($serviceReport->services_max_provided_on)))- {{ \Carbon\Carbon::parse($serviceReport->services_max_provided_on) }} @endif<br />
**Summe der Arbeitsstunden:** {{ $serviceReport->services_sum_hours }}<br />
**Summe der Diätstunden:** {{ $serviceReport->services_sum_allowances }}<br />
**Summe der gefahrenen Kilometer:** {{ $serviceReport->services_sum_kilometres }}

## Kurzbericht

{!! $serviceReport->comment !!}
