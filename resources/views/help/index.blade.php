@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('content')
    <div class="container">
        <h3>Hilfe</h3>

        @unless(empty($names))
            <p>
                Für folgenden Themen ist eine Hilfeseite vorhanden.
            </p>

        @endunless

        @forelse($names as $name)
            <a href="{{ route('help.show', $name) }}">{{ Str::title($name) }}</a>
        @empty
            Es sind keine Hilfethemen im System vorhanden.
        @endforelse
    </div>

@endsection
