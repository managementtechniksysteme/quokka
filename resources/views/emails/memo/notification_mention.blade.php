@component('mail::message')
Hallo,

du wurdest im Aktenvermerk {{ $memo->title }} (Projekt {{ $memo->project->name }} #{{ $memo->number }}) erwähnt.

**Vermerk**<br />
{{ $memo->comment }}

@component('mail::button', ['url' => route('memos.show', $memo)])
    Aktenvermerk in {{ config('app.name') }} öffnen
@endcomponent

Danke,<br />
{{ config('app.name') }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
