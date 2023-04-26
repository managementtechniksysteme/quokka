@component('mail::message')
# Lieferschein {{ $deliveryNote->title }}

**Datum:** {{ $deliveryNote->written_on }}<br />
**Projekt:** {{ $deliveryNote->project->name }}<br />
**Status:**
{{ trans($deliveryNote->status) }}
@switch($deliveryNote->status)
@case('new')
(erstellt am {{ $deliveryNote->created_at }})
@break
@case('signed')
am {{ $deliveryNote->signature()->created_at }}
@break
@case('finished')
am {{ $deliveryNote->updated_at }}
@break
@endswitch

@if($deliveryNote->comment)
**Bemerkungen**<br />
{!! $deliveryNote->comment !!}
@endif

@component('mail::button', ['url' => route('delivery-notes.show', $deliveryNote)])
    Lieferschein in {{ config('app.name') }} öffnen
@endcomponent

Danke,<br />
{{ $deliveryNote->employee->person->name }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
