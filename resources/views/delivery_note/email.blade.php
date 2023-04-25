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
            @include('delivery_note.breadcrumb')

            <h3>
                Lieferschein per Email senden
                <small class="text-muted">{{ $deliveryNote->title }}</small>
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <form class="needs-validation mt-4" action="{{ route('delivery-notes.email', ['delivery_note' => $deliveryNote, 'redirect' => request()->redirect]) }}" method="post" novalidate>
            @csrf

            <div class="row">
                <div class="col-md-4">
                    <p class="d-inline-flex align-items-center mb-1">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#send"></use>
                        </svg>
                        Empfänger
                    </p>
                    <p class="text-muted">
                        Hier können die gewünschten Email Adressen angegeben werden, an welche der Lieferschein per Email gesendet werden soll.
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
                    <div class="col-md-4">
                        <p class="d-inline-flex align-items-center mb-1">
                            <svg class="icon icon-16 mr-2">
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
                            Anhang
                        </label>

                        <div class="row my-2 align-items-center">
                            <div class="col d-inline-flex align-items-center">
                                <svg class="icon attachment-img-preview mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#file-text"></use>
                                </svg>
                                <div>
                                    <div>{{ $deliveryNote->document()->file_name }}</div>
                                    <div class="text-muted">{{ $deliveryNote->document()->human_readable_size }}</div>
                                </div>
                                <a href="{{ $deliveryNote->document()->getUrl() }}" class="stretched-link outline-none"></a>
                            </div>
                            <div class="col-auto ml-auto">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input @error('attachment_ids[]') is-invalid @enderror" name="attachment_ids[]" id="attachment_ids[{{ $deliveryNote->document()->id }}]" value="{{ $deliveryNote->document()->id }}" @if(empty(Request::old()) || (is_array(old('attachment_ids')) && in_array($deliveryNote->document()->id, old('attachment_ids')))) checked @endif>
                                    <label class="custom-control-label" for="attachment_ids[{{ $deliveryNote->document()->id }}]">&nbsp;</label>
                                </div>
                                <div class="invalid-feedback @error('send_signature_request') d-block @enderror">
                                </div>
                            </div>
                        </div>
                        @error('attachment_ids[]')
                        {{ $message }}
                        @enderror

                    </div>
                </div>
            </div>


            <div class="row mt-4">
                <div class="col">
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                        </svg>
                        Lieferschein senden
                    </button>
                </div>
            </div>

        </form>
    </div>
@endsection