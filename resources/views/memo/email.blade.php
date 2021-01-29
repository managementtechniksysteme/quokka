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

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('memo.breadcrumb')

            <h3>
                Aktenvermerk per Email senden
                <small class="text-muted">{{ $memo->title }}</small>
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <form class="needs-validation mt-4" action="{{ route('memos.email', $memo) }}" method="post" novalidate>
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

            @if($memo->attachments()->count() > 0)
                <div class="row mt-4">
                    <div class="col-md-4">
                        <p class="d-inline-flex align-items-center mb-1">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#send"></use>
                            </svg>
                            Anhänge
                        </p>
                        <p class="text-muted">
                            Hier können die gewünschten Anhänge ausgewählt werden, welche dem Email angefügt werden sollen.
                        </p>
                    </div>

                    <div class="col-md-8">
                        <div class="form-group">
                            <label>
                                Anhänge
                            </label>
                            @foreach($memo->attachments() as $attachment)
                                <div class="row my-2 align-items-center">
                                    <div class="col d-inline-flex align-items-center">
                                        @if($attachment->hasGeneratedConversion('thumbnail'))
                                            <img class="attachment-img-preview mr-2" src="{{ $attachment->getUrl('thumbnail') }}" alt="{{ $attachment->file_name }}" />
                                        @else
                                            <svg class="feather attachment-img-preview mr-2">
                                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#file-text"></use>
                                            </svg>
                                        @endif
                                        <div>
                                            <div>{{ $attachment->file_name }}</div>
                                            <div class="text-muted">{{ $attachment->human_readable_size }}</div>
                                        </div>
                                    </div>
                                    <div class="col-auto ml-auto">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input @error('attachment_ids[]') is-invalid @enderror" name="attachment_ids[]" id="attachment_ids[{{ $attachment->id }}]" value="{{ $attachment->id }}" @if(is_array(old('attachment_ids')) && in_array($attachment->id, old('attachment_ids'))) checked @endif>
                                            <label class="custom-control-label" for="attachment_ids[{{ $attachment->id }}]">&nbsp;</label>
                                        </div>
                                        <div class="invalid-feedback @error('send_signature_request') d-block @enderror">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @error('attachment_ids[]')
                                {{ $message }}
                            @enderror
                    </div>
                </div>
            @endif

            <div class="row mt-4">
                <div class="col">
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                        </svg>
                        Aktenvermerk senden
                    </button>
                </div>
            </div>

        </form>
    </div>
@endsection
