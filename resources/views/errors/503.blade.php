@extends('errors.minimal')

@section('title', 'Wartungsarbeiten')
@section('code', '503')
@section('icon')
    <svg viewBox="0 0 16 16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#cone-striped"></use></svg>
@endsection
@section('message', 'Wartungsarbeiten')
@section('description', 'Quokka wird kurz gewartet. Bitte versuche es in ein paar Minuten erneut.')
