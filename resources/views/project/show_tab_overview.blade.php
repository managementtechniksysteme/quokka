@extends('project.show')

@section('tab')
    <div class="row">

        <div class="col-auto">
            <p class="text-muted d-flex align-items-center">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                </svg>
                Firma
            </p>
            <p class="text-muted d-flex align-items-center">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                </svg>
                Zeitraum
            </p>
        </div>

        <div class="col-auto">
            <p>
                {{ $project->company->full_name }}
            </p>

            <p class="d-flex align-items-center">
                {{ $project->starts_on ? $project->starts_on->format('d.m.Y') : 'kein Start angegeben' }}
                <svg class="feather feather-16 mx-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                </svg>
                {{ $project->ends_on ? $project->ends_on->format('d.m.Y') : 'kein Ende angegeben' }}
            </p>
        </div>

    </div>

    <div class="row mt-2">
        <div class="col">
            <p class="text-muted d-flex align-items-center">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
                </svg>
                Bemerkungen
            </p>
            <p>
                @if ($project->comment)
                    @markdown ($project->comment)
                @else
                    keine Bemerkungen angegeben
                @endif
            </p>
        </div>
    </div>
@endsection
