@extends('project.show')

@section('tab')

    <div class="row">
        <div class="col-sm-2">
            <div class="text-muted d-flex align-items-center">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                </svg>
                Firma
            </div>
        </div>
        <div class="col">
            <a href="{{ route('companies.show', $project->company) }}">{{ $project->company->full_name }}</a>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-2">
            <div class="text-muted d-flex align-items-center">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                </svg>
                Zeitraum
            </div>
        </div>
        <div class="col">
            <div class="d-flex align-items-center">
                {{ $project->starts_on ? $project->starts_on : 'kein Start angegeben' }}
                <svg class="feather feather-16 mx-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                </svg>
                {{ $project->ends_on ?? 'kein Ende angegeben' }}
            </div>
        </div>
    </div>

    <div class="text-muted d-flex align-items-center mt-4">
        <svg class="feather feather-16 mr-2">
            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
        </svg>
        Bemerkungen
    </div>
    @if ($project->comment)
        @markdown ($project->comment)
    @else
        keine Bemerkungen angegeben
    @endif
@endsection
