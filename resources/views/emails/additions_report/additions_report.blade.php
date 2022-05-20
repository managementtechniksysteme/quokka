@component('mail::message')
# Regiebericht {{ $additionsReport->project->name }} #{{ $additionsReport->number }}

**Ersteller:** {{ $additionsReport->employee->person->name }}<br />
**Status:**
{{ trans($additionsReport->status) }}
@switch($additionsReport->status)
@case('new')
(erstellt am {{ $additionsReport->created_at }})
@break
@case('signed')
am {{ $additionsReport->signature()->created_at }}
@break
@case('finished')
am {{ $additionsReport->updated_at }}
@break
@endswitch
<br />**Datum:** {{ $additionsReport->services_provided_on }}<br />
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

@component('mail::button', ['url' => route('additions-reports.show', $additionsReport)])
    Regiebericht in {{ config('app.name') }} öffnen
@endcomponent

Danke,<br />
{{ $additionsReport->employee->person->name }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
