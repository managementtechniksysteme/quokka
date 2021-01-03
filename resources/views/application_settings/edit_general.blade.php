@extends('application_settings.edit')

@php use \App\Models\Company; @endphp

@if (old('company_id'))
    @php $currentCompany = Company::find(old('company_id')); @endphp
@endif



@section('tab')
    <form class="needs-validation" action="{{ route('application-settings.update-general') }}" method="post" novalidate>
        @csrf

        <div class="row">
            <div class="col">
                <p class="text-muted d-inline-flex align-items-center mb-1">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                    </svg>
                    Eigene Firma
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
                        Diese Einstellung ist erforderlich, um Mitarbeiter und andere Objekte direkt der eigenen Firma
                        zuweisen zu können.
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="company_id">Firma</label>
                    <company-dropdown :companies="{{ $companies }}" :current_company="{{ $currentCompany ?? 'null' }}" v-cloak></company-dropdown>
                    <div class="invalid-feedback @error('company_id') d-block @enderror">
                        @error('company_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary d-inline-flex align-items-center mt-4">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#save"></use>
            </svg>
            Einstellungen speichern
        </button>
    </form>
@endsection
