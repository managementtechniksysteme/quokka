@extends('user_settings.edit')

@section('tab')
    <div class="row">
        <div class="col">
            <p class="text-muted d-inline-flex align-items-center mb-1">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#pen-tool"></use>
                </svg>
                Unterschrift
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="alert alert-info mt-1" role="alert">
                <div class="d-inline-flex align-items-center">
                    <svg class="feather feather-24 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                    </svg>
                    Die Unterschrift ist erforderlich, um Berichte, wie etwa Servicebrichte automatisch mit einer
                    Unterschrift zu versehen.
                </div>
            </div>
        </div>

    </div>

    <form action="{{ route('user-settings.update-signature') }}" method="post">
        @csrf

        <signature-pad></signature-pad>
        <div class="invalid-feedback @error('signature') d-block @enderror">
            @error('signature')
                {{ $message }}
            @enderror
        </div>


        <div class="row mt-4">
            <div class="col">
                <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
                    </svg>
                    Unterschrift speichern
                </button>
                <a class="btn btn-outline-secondary d-inline-flex align-items-center ml-1" href="">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                    </svg>
                    Zurücksetzen
                </a>
            </div>
        </div>
    </form>

@endsection
