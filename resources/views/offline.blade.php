@extends('layouts.app')

@section('content')
    <div class="container h-100 d-flex align-items-center justify-content-center">
        <div class="text-center">
            <img class="empty-state" src="{{ asset('svg/chilling.svg') }}" alt="relax" />
            <p class="lead text-muted mt-1">Das Gerät kann momentan keine Verbindung zum Server herstellen.</p>
        </div>
    </div>
@endsection
