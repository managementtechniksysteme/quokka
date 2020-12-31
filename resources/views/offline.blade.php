@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            <h3>Offline</h3>
        </div>
    </div>

    <div class="container mt-4">
        <div class="text-center mt-4">
            <img class="empty-state" src="{{ asset('svg/offline.svg') }}" alt="offline" />
            <p class="lead text-muted mt-1">Das Gerät kann momentan keine Verbindung zum Internet herstellen.</p>
        </div>
    </div>
@endsection
