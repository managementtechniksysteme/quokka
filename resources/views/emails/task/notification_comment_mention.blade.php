@component('mail::message')
Hallo,

du wurdest von {{ $comment->employee->person->name }} in einem Kommentar der Aufgabe {{ $comment->task->name }} (Projekt
{{ $comment->task->project->name }}) erwähnt.

**{{ $comment->employee->person->name }}** am {{ $comment->created_at }}<br />
{{ $comment->comment }}

@component('mail::button', ['url' => route('tasks.show', $comment->task)])
    Aufgabe in {{ config('app.name') }} öffnen
@endcomponent

Danke,<br />
{{ config('app.name') }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
