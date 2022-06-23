@component('mail::message')
Hallo,

hier ist deine Zusammenfassung über Benachrichtigungen vom {{ $date }}.

@foreach($notifications as $notification)
**{{ $notification->created_at->format('H:i') }}** - {{ NotificationHelper::header($notification) }} ([Link]({{ $notification->data['route'] }}))<br />
{{ NotificationHelper::text($notification) }}<br /><br />
@endforeach

@component('mail::button', ['url' => route('notifications.index')])
    Ungelesene Benachrichtigungen in {{ config('app.name') }} öffnen
@endcomponent

Danke,<br />
{{ config('app.name') }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
