@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            <h3>
                <svg class="icon icon-baseline mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#help-circle"></use>
                </svg>
                Hilfe
                @if(count($names) > 0)
                    <small class="text-muted">{{ count($names) }} Einträge</small>
                @endif
            </h3>
        </div>
    </div>
    <div class="container my-4">
        @if(count($names) > 0)
            <p>Für folgenden Themen ist eine Hilfeseite vorhanden.</p>
            <ul>
                @foreach($names as $name)
                    <li>
                        <a href="{{ route('help.show', $name) }}">{{ Str::title(trans($name)) }}</a>
                    </li>
                @endforeach
            </ul>
        @else
            <p>Es sind keine Hilfethemen vorhanden.</p>
        @endif
    </div>

@endsection
