@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            @include('service_report.breadcrumb')

            <h3>
                Servicebericht
                <small class="text-muted d-inline-flex align-items-center">
                    {{ $serviceReport->project->name }} #{{ $serviceReport->number }}
                    <svg class="feather feather-16 text-yellow ml-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                    </svg>
                </small>
            </h3>

            <div class="scroll-x d-flex">
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('service-reports.edit', $serviceReport) }}">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                    </svg>
                    Bearbeiten
                </a>
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                    </svg>
                    Email versenden
                </a>
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                    </svg>
                    PDF erstellen
                </a>
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                    </svg>
                    Favorisieren
                </a>
                <div class="dropdown">
                <button class="btn btn-outline-secondary border-0 dropdown-toggle d-inline-flex align-items-center" type="button" id="dropdownCustomerInteractionButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                    </svg>
                    Kundeninteraktion
                </button>

                    <div class="dropdown-menu" aria-labelledby="dropdownCustomerInteractionButton">
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use>
                            </svg>
                            Unterschreiben lassen
                        </a>
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('service-reports.email-signature-request', $serviceReport) }}">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                            </svg>
                            Unterschrift Anfrage sendeen
                        </a>
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('service-reports.email-download-request', $serviceReport) }}">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#download"></use>
                            </svg>
                            Download Link senden
                        </a>
                    </div>
                </div>
                <form action="{{ route('service-reports.destroy', $serviceReport) }}" method="post" >
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-outline-secondary border-0 d-inline-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                        </svg>
                        Entfernen
                    </button>
                </form>
            </div>

        </div>
    </div>

    <div class="container mt-4">
                <div class="row mt-3 mt-md-4">
            <div class="col-sm-2">
                <div class="text-muted d-flex align-items-center">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                    </svg>
                    Projekt
                </div>
            </div>
            <div class="col">
                <a href="{{ route('projects.show', $serviceReport->project) }}">{{ $serviceReport->project->name }}</a>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-2">
                <div class="text-muted d-flex align-items-center">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                    </svg>
                    Techniker
                </div>
            </div>
            <div class="col">
                {{ $serviceReport->employee->person->name }}
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-2">
                <div class="text-muted d-flex align-items-center">
                    <svg class="@if($serviceReport->isNew()) text-primary @elseif($serviceReport->isSigned()) text-warning @else text-success @endif feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#git-commit"></use>
                    </svg>
                    Status
                </div>
            </div>
            <div class="col">
                {{ __($serviceReport->status) }}
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
            </div>
        </div>

        <div class="text-muted d-flex align-items-center mt-4 mb-2">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use>
            </svg>
            Vollbrachte Leistungen
        </div>

        @include('service_report.show_services')

        @if ($serviceReport->comment)
            <div class="text-muted d-flex align-items-center mt-4">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
                </svg>
                Bemerkungen
            </div>
            @markdown ($serviceReport->comment)
        @endif

        @if($serviceReport->attachments()->count() > 0)
            <div class="row text-muted d-flex align-items-center mt-1">
                <div class="col">
                    <div class="d-none d-md-inline-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#paperclip"></use>
                        </svg>
                        Anhänge
                    </div>
                    <a class="d-inline-flex d-md-none d-inline-flex align-items-center" data-toggle="collapse" href="#collapseServiceReportAttachments-{{ $serviceReport->id }}" role="button" aria-expanded="false" aria-controls="collapseServiceReportAttachments-{{ $serviceReport->id }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#paperclip"></use>
                        </svg>
                        Anhänge
                    </a>
                </div>
            </div>
            <div class="d-none d-md-block">
                <div class="row">
                    @foreach($serviceReport->attachments() as $attachment)
                        <div class="col-12 col-md-6 col-lg-3 mt-1">
                            <div class="attachment bg-gray-100 border border-gray-300 d-inline-flex align-items-center position-relative w-100 h-100 p-1">
                                <img class="attachment-img-preview mr-2" src="{{ $attachment->getUrl('thumbnail') }}" alt="{{ $attachment->file_name }}" />
                                <div class="min-w-0">
                                    <div class="min-w-0 text-truncate">{{ $attachment->file_name }}</div>
                                    <div class="text-muted">{{ $attachment->human_readable_size }}</div>
                                </div>
                                <a href="{{ $attachment->getUrl() }}" class="stretched-link outline-none"></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="collapse d-md-none" id="collapseServiceReportAttachments-{{ $serviceReport->id }}">
                <div class="row">
                    @foreach($serviceReport->attachments() as $attachment)
                        <div class="col-12 col-md-6 col-lg-3 mt-1">
                            <div class="attachment bg-gray-100 border border-gray-300 d-inline-flex align-items-center position-relative w-100 h-100 p-1">
                                <img class="attachment-img-preview mr-2" src="{{ $attachment->getUrl('thumbnail') }}" alt="{{ $attachment->file_name }}" />
                                <div class="min-w-0">
                                    <div class="min-w-0 text-truncate">{{ $attachment->file_name }}</div>
                                    <div class="text-muted">{{ $attachment->human_readable_size }}</div>
                                </div>
                                <a href="{{ $attachment->getUrl() }}" class="stretched-link outline-none"></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

@endsection
