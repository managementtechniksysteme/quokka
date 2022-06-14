@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            @include('service_report.breadcrumb')

            <h3>
                Servicebericht
                <small class="text-muted d-inline-flex align-items-center">
                    {{ $serviceReport->project->name }} #{{ $serviceReport->number }}
                    @if(false)
                        <svg class="icon icon-16 text-yellow ml-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                    @endif
                </small>
            </h3>

            <div class="scroll-x d-flex">
                @unless($serviceReport->isFinished())
                    @can('approve', $serviceReport)
                        <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('service-reports.finish', ['service_report' => $serviceReport, 'redirect' => 'show']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                            </svg>
                            Erledigen
                        </a>
                    @endcan
                @endunless
                @can('update', $serviceReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('service-reports.edit', $serviceReport) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Bearbeiten
                    </a>
                @endcan
                @can('email', $serviceReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('service-reports.email', ['service_report' => $serviceReport, 'redirect' => 'show']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                        </svg>
                        Email versenden
                    </a>
                @endcan
                @can('createPdf', $serviceReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('service-reports.download', $serviceReport) }}" target="_blank">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                        </svg>
                        PDF erstellen
                    </a>
                @endcan
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                    </svg>
                    Favorisieren
                </a>
                @can('sign', $serviceReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('service-reports.sign', ['service_report' => $serviceReport, 'redirect' => 'show']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use>
                        </svg>
                        Unterschreiben lassen
                    </a>
                @endcan
                @can('emailSignatureRequest', $serviceReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('service-reports.email-signature-request', ['service_report' => $serviceReport, 'redirect' => 'show']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                        </svg>
                        Unterschrift Anfrage senden
                    </a>
                @endcan
                @can('emailDownloadRequest', $serviceReport)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('service-reports.email-download-request', ['service_report' => $serviceReport, 'redirect' => 'show']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#download"></use>
                        </svg>
                        Download Link senden
                    </a>
                @endcan
                @can('delete', $serviceReport)
                    <form action="{{ route('service-reports.destroy', $serviceReport) }}" method="post" >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-outline-secondary border-0 d-inline-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                            </svg>
                            Entfernen
                        </button>
                    </form>
                @endcan
            </div>

        </div>
    </div>

    <div class="container my-4">
        <div class="row mt-3 mt-md-4">
            <div class="col-sm-2">
                <div class="text-muted d-flex align-items-center">
                    <svg class="icon icon-16 mr-2">
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
                    <svg class="icon icon-16 mr-2">
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
                    <svg class="@if($serviceReport->isNew()) text-primary @elseif($serviceReport->isSigned()) text-warning @else text-success @endif icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#git-commit"></use>
                    </svg>
                    Status
                </div>
            </div>
            <div class="col">
                {{ __($serviceReport->status) }}
                @switch($serviceReport->status)
                    @case('new')
                        @if($serviceReport->signatureRequest)
                            (Anfrage zur Unterschrift gesendet am {{ $serviceReport->signatureRequest->created_at }})
                        @else
                            (erstellt am {{ $serviceReport->created_at }})
                        @endif
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
            <svg class="icon icon-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use>
            </svg>
            Serviceleistungen
        </div>

        @include('service_report.show_services')

        @if ($serviceReport->comment)
            <div class="text-muted d-flex align-items-center mt-4">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
                </svg>
                Bemerkungen
            </div>
            <div class="markdown">
                {!! Html::fromMarkdown($serviceReport->comment) !!}
            </div>
        @endif

        @if($serviceReport->attachments()->count() > 0)
            <div class="row text-muted d-flex align-items-center mt-1">
                <div class="col">
                    <div class="d-none d-md-inline-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#paperclip"></use>
                        </svg>
                        Anhänge
                    </div>
                    <a class="d-inline-flex d-md-none d-inline-flex align-items-center" data-toggle="collapse" href="#collapseServiceReportAttachments-{{ $serviceReport->id }}" role="button" aria-expanded="false" aria-controls="collapseServiceReportAttachments-{{ $serviceReport->id }}">
                        <svg class="icon icon-16 mr-2">
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
                                @if($attachment->hasGeneratedConversion('thumbnail'))
                                    <img class="attachment-img-preview mr-2" src="{{ $attachment->getUrl('thumbnail') }}" alt="{{ $attachment->file_name }}" />
                                @else
                                    <svg class="icon attachment-img-preview mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#file-text"></use>
                                    </svg>
                                @endif
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
                                @if($attachment->hasGeneratedConversion('thumbnail'))
                                    <img class="attachment-img-preview mr-2" src="{{ $attachment->getUrl('thumbnail') }}" alt="{{ $attachment->file_name }}" />
                                @else
                                    <svg class="icon attachment-img-preview mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#file-text"></use>
                                    </svg>
                                @endif
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
