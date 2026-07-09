@extends('layouts.app')

@section('content')
    <div class="q-container">
        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#camera"></use></svg>
                </span>
                <div>
                    <h1 class="q-title">QR-Code scannen</h1>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <qr-scanner url_whitelist="{{ env('APP_URL') }}"></qr-scanner>
        </div>
    </div>
@endsection
