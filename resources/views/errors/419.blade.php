@extends('errors.minimal')

@section('title', 'Sitzung abgelaufen')
@section('code', '419')
@section('icon')
    <svg viewBox="0 0 16 16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-clockwise"></use></svg>
@endsection
@section('message', 'Sitzung abgelaufen')
@section('description', 'Deine Sitzung ist abgelaufen. Bitte lade die Seite neu und versuche es erneut.')

@section('actions')
    <button type="button" class="q-error-btn" onclick="location.reload()">
        <svg viewBox="0 0 16 16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-clockwise"></use></svg>
        Seite neu laden
    </button>
@endsection
