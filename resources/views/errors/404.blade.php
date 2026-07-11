@extends('errors.minimal')

@section('title', 'Seite nicht gefunden')
@section('code', '404')
@section('icon')
    <svg viewBox="0 0 16 16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#signpost-split"></use></svg>
@endsection
@section('message', 'Seite nicht gefunden')
@section('description', 'Die angeforderte Seite existiert nicht oder wurde verschoben.')
