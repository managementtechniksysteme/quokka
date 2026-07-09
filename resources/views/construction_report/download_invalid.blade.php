@extends('layouts.app')

@section('content')
    <div class="q-container">
        <div class="q-card text-center" style="padding: 2.75rem 1.5rem;">
            <svg class="q-empty-icon" style="margin-bottom:.5rem"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x-circle"></use></svg>
            <p class="lead text-muted mt-2 mb-0">
                Unter diesem Link ist kein Bautagesbericht zum Herunterladen vorhanden.<br />
                Der Bautagesbericht wurde wahrscheinlich bereits heruntergeladen oder er existiert nicht.
            </p>
        </div>
    </div>
@endsection
