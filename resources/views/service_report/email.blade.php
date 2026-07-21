@extends('layouts.app')

@if (old('email_to'))
    @php $currentTo = json_encode(old('email_to')); @endphp
@endif

@if (old('email_cc'))
    @php $currentCC = json_encode(old('email_cc')); @endphp
@endif

@if (old('email_bcc'))
    @php $currentBCC = json_encode(old('email_bcc')); @endphp
@endif

@section('mobile-detail-bar')
    <a href="{{ url()->previous() }}" class="q-appbar__btn" aria-label="Abbrechen">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>
    </a>
    <span class="q-appbar__title">{{ $serviceReport->project->name }} #{{ $serviceReport->number }}</span>
@endsection

@section('content')
    <div class="q-container">

        <div class="d-none d-md-block">
            @include('service_report.breadcrumb')
        </div>

        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">{{ $serviceReport->project->name }} #{{ $serviceReport->number }}</div>
                    <h1 class="q-title">Servicebericht senden</h1>
                </div>
            </div>
        </div>

        <form class="q-form needs-validation mt-2 mt-md-4" action="{{ route('service-reports.email', ['service_report' => $serviceReport, 'redirect' => request()->redirect]) }}" method="post" novalidate>
            @csrf

            <div class="q-form-section">
                <div class="q-form-section__head">Empfänger</div>
                <div class="q-form-section__body">
                    <email-selector :people="{{ $people }}" :current_to="{{ $currentTo ?? '[]'}}" :current_cc="{{ $currentCC ?? '[]' }}" :current_bcc="{{ $currentBCC ?? '[]' }}"></email-selector>
                    <div class="invalid-feedback @error('email_to') d-block @enderror @error('email_to.*') d-block @enderror @error('email_cc') d-block @enderror @error('email_cc.*') d-block @enderror @error('email_bcc') d-block @enderror @error('email_bcc.*') d-block @enderror">
                        @error('email_to'){{ $message }}@enderror
                        @error('email_to.*'){{ $message }}@enderror
                        @error('email_cc'){{ $message }}@enderror
                        @error('email_cc.*'){{ $message }}@enderror
                        @error('email_bcc'){{ $message }}@enderror
                        @error('email_bcc.*'){{ $message }}@enderror
                    </div>
                </div>
            </div>

            @if($serviceReport->attachments()->count() > 0)
                <div class="q-form-section">
                    <div class="q-form-section__head">Anhänge</div>
                    <div class="q-form-section__body">
                        @foreach($serviceReport->attachments() as $attachment)
                            <div class="d-flex align-items-center gap-3 py-2{{ $loop->first ? '' : ' border-top' }}" style="border-color: var(--q-border-2)">
                                <div class="flex-shrink-0">
                                    @if($attachment->hasGeneratedConversion('thumbnail'))
                                        <img class="attachment-img-preview" src="{{ $attachment->getUrl('thumbnail') }}" alt="{{ $attachment->file_name }}" />
                                    @else
                                        <span class="q-attach__icon"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#file-text"></use></svg></span>
                                    @endif
                                </div>
                                <div class="flex-grow-1 min-width-0">
                                    <a href="{{ $attachment->getUrl() }}" class="fw-semibold text-decoration-none" style="font-size:.85rem" target="_blank">{{ $attachment->file_name }}</a>
                                    <div class="text-muted" style="font-size:.78rem">{{ $attachment->human_readable_size }}</div>
                                </div>
                                <div class="form-check form-switch flex-shrink-0 mb-0">
                                    <input type="checkbox" class="form-check-input @error('attachment_ids[]') is-invalid @enderror" name="attachment_ids[]" id="attachment_{{ $attachment->id }}" value="{{ $attachment->id }}" @if(empty(Request::old()) || (is_array(old('attachment_ids')) && in_array($attachment->id, old('attachment_ids')))) checked @endif>
                                    <label class="form-check-label" for="attachment_{{ $attachment->id }}">&nbsp;</label>
                                </div>
                            </div>
                        @endforeach
                        @error('attachment_ids[]')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            @endif
            <div class="q-form-actions q-form-actions--solo-mobile">
                <a class="btn q-btn d-none d-md-inline-flex align-items-center gap-2" href="{{ url()->previous() }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x"></use></svg>
                    Abbrechen
                </a>
                <button type="submit" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#send"></use></svg>
                    <span class="d-none d-md-inline">Servicebericht senden</span>
                    <span class="d-inline d-md-none">Senden</span>
                </button>
            </div>

        </form>

    </div>
@endsection
