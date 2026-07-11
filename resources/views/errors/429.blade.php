@extends('errors.minimal')

@section('title', 'Zu viele Anfragen')
@section('code', '429')
@section('icon')
    <svg viewBox="0 0 16 16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#hourglass-split"></use></svg>
@endsection
@section('message', 'Zu viele Anfragen')
@section('description', 'Du hast zu viele Anfragen in kurzer Zeit gesendet. Bitte warte einen Moment und versuche es erneut.')
