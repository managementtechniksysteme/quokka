# Zusammenfassung des Serviceberichtes

**Techniker:** {{ $serviceReport->employee->person->name }}<br />
**Projekt:** {{ $serviceReport->project->name }} #{{ $serviceReport->number }}<br />
**Zeitraum der erbrachten Leistungen:** {{ $services_min_provided_on }} @if($services_min_provided_on->ne($services_max_provided_on))- {{ $services_max_provided_on }} @endif<br />
**Summe der Arbeitsstunden:** {{ $services_sum_hours }}<br />
**Summe der gefahrenen Kilometer:** {{ $services_sum_kilometres }}

**Kurzbericht**<br />
{!! $serviceReport->comment !!}
