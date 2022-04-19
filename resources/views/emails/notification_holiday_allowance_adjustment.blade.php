@component('mail::message')
Hallo,

dein verfügbarer Urlaub wurde um {{ $holidayAllowanceDifference }} {{ $holidayServiceUnit }} {{ $directionString }}.
Dein aktueller Stand beträgt {{ $currentHolidayAllowance }} {{ $holidayServiceUnit }}.

Danke,<br />
{{ config('app.name') }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
