@extends('layouts.app')

@section('content')
    <div class="container">
        <qr-scanner url_whitelist="{{ env('APP_URL') }}"></qr-scanner>
    </div>
@endsection
