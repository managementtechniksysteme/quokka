@component('mail::message')
# Aktenvermerk {{ $memo->project->name }} #{{ $memo->number }}

**Titel:** {{ $memo->title }}<br />
**Von:** {{ $memo->employeeComposer->person->name }}<br />
**An:** {{ $memo->personRecipient->name }}<br />
**Besprechungsdatum:** {{ $memo->meeting_held_on }}<br />
**Verfassungsdatum:** {{ $memo->created_at }}<br />
@if($memo->next_meeting_on)
**Nächster Termin:** {{ $memo->next_meeting_on }}<br />
@endif

@if($memo->presentPeople->count() > 0)
**Anwesende Personen:**<br />
@foreach($memo->presentPeople as $person)
{{ $person->name }}@unless($loop->last), @endunless
@endforeach
@endif


@if($memo->notifiedPeople->count() > 0)
**Verteiler:**<br />
@foreach($memo->notifiedPeople as $person)
{{ $person->name }}@unless($loop->last), @endunless
@endforeach
@endif


**Vermerk**<br />
{!! $memo->comment !!}

Danke,<br />
{{ $memo->employeeComposer->person->name }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
