@component('mail::message')
Hallo,

ein Kommentar von {{ $comment->employee->person->name }} in der Aufgabe {{ $comment->task->name }} (Projekt
{{ $comment->task->project->name }}), an der du beteiligt bist, wurde @if($isNew) angelegt @else bearbeitet @endif .

**{{ $comment->employee->person->name }}** am {{ $comment->updated_at }}<br />
{{ $comment->comment }}

@component('mail::button', ['url' => route('tasks.show', $comment->task)])
    Aufgabe in {{ config('app.name') }} öffnen
@endcomponent

Danke,<br />
{{ config('app.name') }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
