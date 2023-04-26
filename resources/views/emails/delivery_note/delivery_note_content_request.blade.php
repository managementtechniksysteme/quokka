# Zusammenfassung des Lieferscheins

**Datum:** {{ $deliveryNote->written_on }}<br />
**Projekt:** {{ $deliveryNote->project->name }}<br />

@if($deliveryNote->comment)
**Bemerkungen**<br />
{!! $deliveryNote->comment !!}
@endif
