@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            <h3>
                <svg class="icon icon-baseline text-muted mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
                </svg>
                Einstellungen
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <div class="row mt-4">
            <div class="d-none d-lg-block col-lg-3">
                <div class="menu border-right pr-3">
                    <a class="menu-item @if (request()->tab == 'general') active @endif rounded text-muted d-flex align-items-center p-2" href="{{ route('user-settings.edit', ['tab' => 'general']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
                        </svg>
                        Allgemeines
                        @unless(Auth::user()->signature())
                            <svg class="text-red icon icon-16 ml-auto">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                            </svg>
                        @endunless
                    </a>
                    <a class="menu-item @if (request()->tab == 'interface') active @endif rounded text-muted d-flex align-items-center p-2" href="{{ route('user-settings.edit', ['tab' => 'interface']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#layout"></use>
                        </svg>
                        Aussehen
                    </a>
                    <a class="menu-item @if (request()->tab == 'notifications') active @endif rounded text-muted d-flex align-items-center p-2" href="{{ route('user-settings.edit', ['tab' => 'notifications']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#bell"></use>
                        </svg>
                        Benachrichtigungen
                    </a>
                    <a class="menu-item @if (request()->tab == 'security') active @endif rounded text-muted d-flex align-items-center p-2" href="{{ route('user-settings.edit', ['tab' => 'security']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#shield"></use>
                        </svg>
                        Sicherheit
                    </a>
                </div>
            </div>

            <div class="menu d-block d-lg-none col mb-4">
                <div class="scroll-x border-bottom pb-1">
                    <a class="menu-item @if (request()->tab == 'general') active @endif rounded text-muted d-inline-flex align-items-center p-2" href="{{ route('user-settings.edit', ['tab' => 'general']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
                        </svg>
                        Allgemeines
                        @unless(Auth::user()->signature())
                            <svg class="text-red icon icon-16 ml-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                            </svg>
                        @endunless
                    </a>
                    <a class="menu-item @if (request()->tab == 'interface') active @endif rounded text-muted d-inline-flex align-items-center p-2" href="{{ route('user-settings.edit', ['tab' => 'interface']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#layout"></use>
                        </svg>
                        Aussehen
                    </a>
                    <a class="menu-item @if (request()->tab == 'notifications') active @endif rounded text-muted d-inline-flex align-items-center p-2" href="{{ route('user-settings.edit', ['tab' => 'notifications']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#bell"></use>
                        </svg>
                        Benachrichtigungen
                    </a>
                    <a class="menu-item @if (request()->tab == 'security') active @endif rounded text-muted d-inline-flex align-items-center p-2" href="{{ route('user-settings.edit', ['tab' => 'security']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#shield"></use>
                        </svg>
                        Sicherheit
                    </a>
                </div>
            </div>

            <div class="col-lg-9">
                @yield ('tab')
            </div>
        </div>

    </div>
@endsection
