@extends('errors.minimal')

@section('title', 'Zugriff verweigert')
@section('code', '403')
@section('icon')
    <svg viewBox="0 0 16 16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#lock"></use></svg>
@endsection
@section('message', 'Zugriff verweigert')
@section('description', 'Du hast keine Berechtigung, um auf diese Seite zuzugreifen.')
