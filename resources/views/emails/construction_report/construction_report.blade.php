@component('mail::message')
# Bautagesbericht {{ $constructionReport->project->name }} #{{ $constructionReport->number }}

**Ersteller:** {{ $constructionReport->employee->person->name }}<br />
**Status:**
{{ trans($constructionReport->status) }}
@switch($constructionReport->status)
@case('new')
(erstellt am {{ $constructionReport->created_at }})
@break
@case('signed')
am {{ $constructionReport->signature()->created_at }}
@break
@case('finished')
am {{ $constructionReport->updated_at }}
@break
@endswitch
<br />**Datum:** {{ $constructionReport->services_provided_on }}<br />
**Wetter:** {{ trans($constructionReport->weather) }}
({{ $constructionReport->minimum_temperature }}@if($constructionReport->minimum_temperature !== $constructionReport->maximum_temperature) bis {{ $constructionReport->maximum_temperature }}@endif °C)<br />
**Personalstand:**<br />
@foreach($constructionReport->involvedEmployees as $employee)
{{ $employee->person->name }}@unless($loop->last), @endunless
@endforeach
@if($constructionReport->presentPeople->count() > 0)
<br />**Anwesende Personen:**<br />
@foreach($constructionReport->presentPeople as $person)
{{ $person->name }}@unless($loop->last), @endunless
@endforeach
@endif
@if($constructionReport->other_visitors)
<br />**Sonstige Besucher:**<br />
{{ $constructionReport->other_visitors }}
@endif
@if($constructionReport->inspection_comment)
<br />**Güte- und Funktionsprüfung:**<br />
{{ $constructionReport->inspection_comment }}
@endif
@if($constructionReport->missing_documents)
<br />**Fehlende Ausführungsunterlagen:**<br />
{{ $constructionReport->missing_documents }}
@endif
@if($constructionReport->special_occurrences)
<br />**Besondere Vorkommnisse:**<br />
{{ $constructionReport->special_occurrences }}
@endif
@if($constructionReport->imminent_danger)
<br />**Gefahr in Verzug:**<br />
{{ $constructionReport->imminent_danger }}
@endif
@if($constructionReport->concerns)
<br />**Bedenken:**<br />
{{ $constructionReport->concerns }}
@endif

<br /><br />**Leistungsfortschritt**<br />
{!! $constructionReport->comment !!}

@component('mail::button', ['url' => route('construction-reports.show', $constructionReport)])
    Bautagesbericht in {{ config('app.name') }} öffnen
@endcomponent

Danke,<br />
{{ $constructionReport->employee->person->name }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
