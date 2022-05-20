# Zusammenfassung des Regieberichtes

**Bauvorhaben:** {{ $additionsReport->project->name }} #{{ $additionsReport->number }}<br />
**Datum:** {{ $additionsReport->services_provided_on }}<br />
**Regiestunden:** {{ Number::toLocal($additionsReport->hours) }}<br />
**Wetter:** {{ trans($additionsReport->weather) }}
({{ $additionsReport->minimum_temperature }}@if($additionsReport->minimum_temperature !== $additionsReport->maximum_temperature) bis {{ $additionsReport->maximum_temperature }}@endif °C)<br />
**Personalstand:**<br />
@foreach($additionsReport->involvedEmployees as $employee)
{{ $employee->person->name }}@unless($loop->last), @endunless
@endforeach
@if($additionsReport->presentPeople->count() > 0)
<br />**Anwesende Personen:**<br />
@foreach($additionsReport->presentPeople as $person)
{{ $person->name }}@unless($loop->last), @endunless
@endforeach
@endif
@if($additionsReport->other_visitors)
<br />**Sonstige Besucher:**<br />
{{ $additionsReport->other_visitors }}
@endif
@if($additionsReport->inspection_comment)
<br />**Güte- und Funktionsprüfung:**<br />
{{ $additionsReport->inspection_comment }}
@endif
@if($additionsReport->missing_documents)
<br />**Fehlende Ausführungsunterlagen:**<br />
{{ $additionsReport->missing_documents }}
@endif
@if($additionsReport->special_occurrences)
<br />**Besondere Vorkommnisse:**<br />
{{ $additionsReport->special_occurrences }}
@endif
@if($additionsReport->imminent_danger)
<br />**Gefahr in Verzug:**<br />
{{ $additionsReport->imminent_danger }}
@endif
@if($additionsReport->concerns)
<br />**Bedenken:**<br />
{{ $additionsReport->concerns }}
@endif

**Leistungsfortschritt**<br />
{!! $additionsReport->comment !!}
