# Zusammenfassung des Lieferscheins

**Projekt:** {{ $deliveryNote->project->name }}<br />

@if($deliveryNote->comment)
**Bemerkungen**<br />
{!! $deliveryNote->comment !!}
@endif
