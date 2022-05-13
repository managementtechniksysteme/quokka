@extends('errors.minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Server Error'))
@section('support')
    @if($exceptionUuid)
        <div class="flex items-center mx-auto mt-4 sm:px-6 lg:px-8">
            <div class="px-4 text-sm text-gray-500 uppercase">
                {{ __('Für Hilfe gib bitte folgende Fehler Identifikation an.') }}
            </div>
        </div>

        <div class="flex items-center mx-auto mt-2 sm:px-6 lg:px-8">
            <div class="px-4 text-sm text-gray-500 tracking-wider">
                {{ $exceptionUuid }}
            </div>
        </div>


        <button id="uuid-button" class="mx-auto bg-gray-100 outline-none mt-2" data-clipboard-text="{{ $exceptionUuid }}">
            <svg class="icon-bs icon-baseline text-gray-500 mr-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use>
            </svg>
            <span id="uuid-button-text" class="text-sm text-gray-500 uppercase tracking-wider">Kopieren</span>
        </button>

    @endif
@endsection
