@component('mail::message')
# Aufgabe {{ $task->name }}

**Projekt:** {{ $task->project->name }}<br />
@if($task->starts_on || $task->ends_on)
**Zeitraum:** {{ $task->starts_on ?? 'nicht angegeben' }}@if($task->ends_on) - {{ $task->ends_on }} @endif<br />
@endif
@if($task->due_on)
**Fälligkeitsdatum:** {{ $task->due_on }}<br />
@endif
**Priorität:** {{ trans($task->priority) }}<br />
**Status:** {{ trans($task->status) }}<br />
**Verrechnungsstatus:** {{ trans($task->billed_string) }}


**Verantwortlicher Mitarbeiter:** {{ $task->responsibleEmployee->person->name }}

@if($task->involvedEmployees->count() > 0)
**Beteiligte Mitarbeiter:**<br />
@foreach($task->involvedEmployees as $employee)
{{ $employee->person->name }}@unless($loop->last), @endunless
@endforeach
@endif


@if($task->comment)
**Bemerkungen**<br />
{!! $task->comment !!}
@endif

@if($task->comments->count() > 0)
---
**Kommentare**

@foreach($task->comments as $comment)
**{{ $comment->employee->person->name }}** am {{ $comment->created_at }}<br />
{{ $comment->comment }}


@endforeach
@endif

@component('mail::button', ['url' => route('tasks.show', $task)])
    Aufgabe in {{ config('app.name') }} öffnen
@endcomponent

Danke,<br />
{{ config('app.name') }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
