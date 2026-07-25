@extends('layouts.app')

@section('content')
    <div class="q-container">
        {{-- Mobile: the app bar already carries "QR-Code scannen" + its own
             camera icon (partials/navbar.blade.php's $mobilePageLabels) —
             identical to the desktop title here, so this whole head is
             desktop-only rather than repeating it (2026-07-22, same "double
             headers" fix as search/notification/help/latest-changes). --}}
        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#camera"></use></svg>
                </span>
                <div>
                    <h1 class="q-title">QR-Code scannen</h1>
                </div>
            </div>
        </div>

        <div class="mt-2 mt-md-4">
            <qr-scanner url_whitelist="{{ env('APP_URL') }}"></qr-scanner>
        </div>
    </div>
@endsection
