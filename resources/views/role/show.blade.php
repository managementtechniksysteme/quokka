@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            @include('role.breadcrumb')

            <h3>
                Rolle
                <small class="text-muted d-inline-flex align-items-center">
                    {{ $role->name }}
                    @if($role->permissions_count)
                        ({{ trans_choice('messages.permissions', $role->permissions_count) }})
                    @endif
                    @if(false)
                        <svg class="icon icon-16 text-yellow ml-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                    @endif
                </small>
            </h3>

            <div class="scroll-x d-flex">
                @can('update', $role)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('roles.edit', $role) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Bearbeiten
                    </a>
                @endcan
                @can('create', \Spatie\Permission\Models\Role::class)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('roles.create', ['template' => $role]) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#copy"></use>
                        </svg>
                        Kopieren
                    </a>
                @endcan
                @can('email', $role)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                        </svg>
                        Email versenden
                    </a>
                @endcan
                @can('createPdf', $role)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#" target="_blank">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                        </svg>
                        PDF erstellen
                    </a>
                @endcan
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                    </svg>
                    Favorisieren
                </a>
                @can('delete', $role)
                    <form action="{{ route('roles.destroy', $role) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-outline-secondary border-0 d-inline-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                            </svg>
                            Entfernen
                        </button>
                    </form>
                @endcan
            </div>

        </div>
    </div>

    <div class="container my-4">
        <fieldset disabled>
            @component('role.fields', [ 'role' => $role ])
            @endcomponent
        </fieldset>
    </div>
@endsection
