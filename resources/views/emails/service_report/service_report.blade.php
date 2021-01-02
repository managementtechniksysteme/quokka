@component('mail::message')
# Servicebericht {{ $serviceReport->project->name }} #{{ $serviceReport->number }}

**Techniker:** {{ $serviceReport->employee->person->name }}<br />
**Status:**
{{ trans($serviceReport->status) }}
@switch($serviceReport->status)
@case('new')
(erstellt am {{ $serviceReport->created_at }})
@break
@case('signed')
am {{ $serviceReport->signature()->created_at }}
@break
@case('finished')
am {{ $serviceReport->updated_at }}
@break
@endswitch

**Vollbrachte Leistungen**
@component('mail::table')
| Datum                       | Stunden               | Diäten                     | gefahrene Kilometer        |
|:--------------------------- |:--------------------- |:-------------------------- |:-------------------------- |
@foreach($serviceReport->services as $service)
| {{ $service->provided_on }} | {{ $service->hours }} | {{ $service->allowances }} | {{ $service->kilometres }} |
@endforeach
@endcomponent

**Kurzbericht**<br />
{!! $serviceReport->comment !!}

@component('mail::button', ['url' => route('service-reports.show', $serviceReport)])
    Servicebericht in {{ config('app.name') }} öffnen
@endcomponent

Danke,<br />
{{ $serviceReport->employee->person->name }}<br />
MTS Management Technik Systeme GmbH & CO KG

@include('emails.partials.info_footer')
@endcomponent
