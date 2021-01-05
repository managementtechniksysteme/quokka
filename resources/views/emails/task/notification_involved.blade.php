@component('mail::message')
Hallo,

die Aufgabe {{ $task->name }} (Projekt {{ $task->project->name }}), an der du beteiligt bist, wurde
@if($isNew) angelegt @else bearbeitet @endif .

@if($task->comment)
**Bemerkungen**<br />
{!! $task->comment !!}
@endif

@component('mail::button', ['url' => route('tasks.show', $task)])
    Aufgabe in {{ config('app.name') }} öffnen
@endcomponent

Danke,<br />
{{ config('app.name') }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
