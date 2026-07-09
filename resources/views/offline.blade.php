@extends('layouts.app')

@section('content')
    <div class="container h-100 d-flex align-items-center justify-content-center">
        <div class="q-empty-state">
            <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#wifi-off"></use></svg>
            <p>Das Gerät kann momentan keine Verbindung zum Server herstellen.</p>
        </div>
    </div>
@endsection
