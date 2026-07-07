@extends('layouts.app')

@section('content')
    <div class="q-container">

        @include('service_report.breadcrumb')

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-avatar">
                    <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Servicebericht · #{{ $serviceReport->number }}</div>
                    <h1 class="q-title">{{ $serviceReport->project->name }}</h1>
                    <div class="q-meta">
                        <span class="q-status q-status--{{ $serviceReport->status }}">{{ $serviceReport->status_label }}</span>

                        <span class="q-chip">
                            @switch($serviceReport->status)
                                @case('signed')
                                    <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use></svg>
                                    {{ optional($signature)->created_at }}
                                    @break
                                @case('finished')
                                    <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use></svg>
                                    {{ $serviceReport->updated_at }}@if($serviceReport->activities->last()?->causer) · {{ Str::upper($serviceReport->activities->last()->causer->username) }}@endif
                                    @break
                                @default
                                    @if($serviceReport->signatureRequest)
                                        <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#send"></use></svg>
                                        {{ $serviceReport->signatureRequest->created_at }}
                                    @else
                                        <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use></svg>
                                        {{ $serviceReport->created_at }}
                                    @endif
                            @endswitch
                        </span>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @unless($serviceReport->isFinished())
                    @can('approve', $serviceReport)
                        <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('service-reports.finish', ['service_report' => $serviceReport, 'redirect' => 'show']) }}">
                            <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check"></use></svg>
                            Erledigen
                        </a>
                    @endcan
                @endunless
                @can('update', $serviceReport)
                    <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('service-reports.edit', $serviceReport) }}">
                        <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use></svg>
                        Bearbeiten
                    </a>
                @endcan

                <div class="dropdown">
                    <button class="q-kebab" type="button" id="serviceReportShowDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#more-vertical"></use></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="serviceReportShowDropdown">
                        @can('email', $serviceReport)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('service-reports.email', ['service_report' => $serviceReport, 'redirect' => 'show']) }}">
                                <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use></svg>
                                Email versenden
                            </a>
                        @endcan
                        @can('createPdf', $serviceReport)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('service-reports.download', $serviceReport) }}" target="_blank">
                                <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use></svg>
                                PDF erstellen
                            </a>
                        @endcan
                        @can('emailSignatureRequest', $serviceReport)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('service-reports.email-signature-request', ['service_report' => $serviceReport, 'redirect' => 'show']) }}">
                                <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use></svg>
                                Unterschrift Anfrage senden
                            </a>
                        @endcan
                        @can('emailDownloadRequest', $serviceReport)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('service-reports.email-download-request', ['service_report' => $serviceReport, 'redirect' => 'show']) }}">
                                <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#download"></use></svg>
                                Download Link senden
                            </a>
                        @endcan
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use></svg>
                            Favorisieren
                        </a>
                        @can('delete', $serviceReport)
                            <form action="{{ route('service-reports.destroy', $serviceReport) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item dropdown-item-danger d-inline-flex align-items-center">
                                    <svg class="icon icon-16 me-2"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use></svg>
                                    Entfernen
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <div class="q-statbar mb-4">
            <div class="q-statbar__cell">
                <span class="q-statbar__label">Projekt</span>
                <span class="q-statbar__value text-truncate">
                    <a href="{{ route('projects.show', $serviceReport->project) }}">{{ $serviceReport->project->name }}</a>
                </span>
            </div>
            <div class="q-statbar__cell">
                <span class="q-statbar__label">Techniker</span>
                <span class="q-statbar__value">{{ $serviceReport->employee->person->name }}</span>
            </div>
            <div class="q-statbar__cell">
                <span class="q-statbar__label">Stunden</span>
                <span class="q-statbar__value">{{ Number::toLocal($serviceReport->total_hours) }} h</span>
            </div>
            @if($serviceReport->total_kilometres > 0)
                <div class="q-statbar__cell">
                    <span class="q-statbar__label">Kilometer</span>
                    <span class="q-statbar__value">{{ Number::toLocal($serviceReport->total_kilometres) }} km</span>
                </div>
            @endif
        </div>

        <div class="q-detail">
            <div class="d-flex flex-column gap-3">
                {{-- Serviceleistungen --}}
                <div class="q-card">
                    <div class="q-card__head">Serviceleistungen</div>
                    @include('service_report.show_services')
                </div>

                {{-- Bemerkungen --}}
                @if ($serviceReport->comment)
                    <div class="q-card">
                        <div class="q-card__head">Bemerkungen</div>
                        <div class="q-card__body">
                            <div class="markdown">
                                {!! Html::fromMarkdown($serviceReport->comment) !!}
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Anhänge --}}
                @if($serviceReport->attachments()->count() > 0)
                    <div class="q-card">
                        <div class="q-card__head">Anhänge</div>
                        <div class="q-card__body">
                            <div class="row g-2">
                                @foreach($serviceReport->attachments() as $attachment)
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <a href="{{ $attachment->getUrl() }}" class="q-attach">
                                            @if($attachment->hasGeneratedConversion('thumbnail'))
                                                <img class="q-attach__preview" src="{{ $attachment->getUrl('thumbnail') }}" alt="{{ $attachment->file_name }}" />
                                            @else
                                                <span class="q-attach__preview q-attach__preview--icon">
                                                    <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#file-text"></use></svg>
                                                </span>
                                            @endif
                                            <span class="min-w-0">
                                                <span class="q-attach__name text-truncate d-block">{{ $attachment->file_name }}</span>
                                                <span class="q-attach__size">{{ $attachment->human_readable_size }}</span>
                                            </span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Unterschrift --}}
            <div class="q-card">
                <div class="q-card__head d-flex align-items-center justify-content-between">
                    <span>Unterschrift</span>
                    @if($signature)
                        <span class="q-chip q-chip--success">
                            <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check"></use></svg>
                            vorhanden
                        </span>
                    @else
                        <span class="q-chip q-chip--warning">
                            <svg class="icon icon-12"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use></svg>
                            ausstehend
                        </span>
                    @endif
                </div>
                <div class="q-card__body">
                    @if($signature)
                        <img class="q-sign-img" src="{{ $signature->getUrl() }}" alt="Unterschrift" />
                        <div class="q-sign-date">unterschrieben am {{ $signature->created_at }}</div>
                    @else
                        <div class="q-signbox">
                            <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use></svg>
                            Keine Unterschrift
                        </div>
                        @can('sign', $serviceReport)
                            <a class="btn btn-primary text-white w-100 d-inline-flex align-items-center justify-content-center gap-2" href="{{ route('service-reports.sign', ['service_report' => $serviceReport, 'redirect' => 'show']) }}">
                                <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use></svg>
                                Unterschreiben lassen
                            </a>
                        @endcan
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
