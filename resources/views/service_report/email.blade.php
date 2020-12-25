@extends('layouts.app')

@php
    use \App\Models\Project;
@endphp

@if (old('email_to'))
    @php $currentTo = json_encode(old('email_to')); @endphp
@endif

@if (old('email_cc'))
    @php $currentCC = json_encode(old('email_cc')); @endphp
@endif

@if (old('email_bcc'))
    @php $currentBCC = json_encode(old('email_bcc')); @endphp
@endif

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('service_report.breadcrumb')

            <h3>
                Servicebericht per Email senden
                <small class="text-muted">{{ $serviceReport->project->name }} #{{ $serviceReport->number }}</small>
            </h3>
        </div>
    </div>

    <div class="container mt-4">
        <form class="needs-validation mt-4" action="{{ route('service-reports.email', $serviceReport) }}" method="post" novalidate>
            @csrf

            <div class="row">
                <div class="col-md-4">
                    <p class="d-inline-flex align-items-center mb-1">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#send"></use>
                        </svg>
                        Empfänger
                    </p>
                    <p class="text-muted">
                        Hier können die gewünschten Email Adressen angegeben werden, an welche der Servicebericht per Email gesendeet werden soll.
                    </p>
                </div>

                <div class="col-md-8">

                    <div class="form-group">
                        <label for="email">Empfänger</label>
                        <email-selector :people="{{ $people }}" :current_to="{{ $currentTo ?? '[]'}}" :current_cc="{{ $currentCC ?? '[]' }}" :current_bcc="{{ $currentBCC ?? '[]' }}"></email-selector>
                        <div class="invalid-feedback @error('email_to') d-block @enderror @error('email_to.*') d-block @enderror @error('email_cc') d-block @enderror @error('email_cc.*') d-block @enderror @error('email_bcc') d-block @enderror @error('email_bcc.*') d-block @enderror">
                            @error('email_to')
                                {{ $message }}
                            @enderror
                            @error('email_to.*')
                                {{ $message }}
                            @enderror
                            @error('email_cc')
                                {{ $message }}
                            @enderror
                            @error('email_cc.*')
                                {{ $message }}
                            @enderror
                            @error('email_bcc')
                                {{ $message }}
                            @enderror
                            @error('email_bcc.*')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                </div>
            </div>

            <div class="row mt-4">
                <div class="col">
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#send"></use>
                        </svg>
                        Servicebericht senden
                    </button>
                </div>
            </div>

        </form>
    </div>
@endsection
