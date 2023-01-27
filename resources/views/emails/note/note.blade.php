@component('mail::message')
# Notiz vom {{ $note->created_at->format('d.m.Y, H:i') }}

@if($note->title)
**Titel:** {{ $note->title }}<br />
@endif

**Notiz**<br />
{!! $note->comment !!}

Danke,<br />
{{ config('app.name') }}<br />
MTS Management Technik Systeme GmbH & CO KG

    @include('emails.partials.info_footer')
@endcomponent
