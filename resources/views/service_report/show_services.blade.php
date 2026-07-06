<div class="q-lines__head">
    <span>Datum</span>
    <span class="q-lines__num">Stunden</span>
    <span class="q-lines__num">Gef. km</span>
</div>
@forelse($serviceReport->services as $service)
    <div class="q-lines__row">
        <span>{{ $service->provided_on }}</span>
        <span class="q-lines__num">{{ Number::toLocal($service->hours) }}</span>
        <span class="q-lines__num @unless($service->kilometres) q-lines__num--muted @endunless">{{ Number::toLocal($service->kilometres) }}</span>
    </div>
@empty
    <div class="q-lines__empty">Keine Serviceleistungen erfasst.</div>
@endforelse
@unless($serviceReport->services->isEmpty())
    <div class="q-lines__sum">
        <span class="q-lines__sumlabel">Summe</span>
        <span class="q-lines__sumval">{{ Number::toLocal($serviceReport->total_hours) }} h</span>
        <span class="q-lines__sumval">{{ Number::toLocal($serviceReport->total_kilometres) }} km</span>
    </div>
@endunless
