@component('mail::message')
Hallo,

Quokka ist nun in der Version @version('compact') zur Verwendung verfügbar.

@component('mail::button', ['url' => route('changelog.show')])
    Versionshinweise öffnen
@endcomponent

Danke,<br />
{{ config('app.name') }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
