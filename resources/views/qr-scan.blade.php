@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            <h3>
                <svg class="icon icon-baseline mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#camera"></use>
                </svg>
                QR-Code scannen
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <qr-scanner url_whitelist="{{ env('APP_URL') }}"></qr-scanner>
    </div>
@endsection
