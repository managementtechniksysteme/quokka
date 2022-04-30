<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use>
            </svg>
            Abrechnung
        </p>
        <p class="text-muted">
            Berechtigungen für die Abrechnung.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('accounting_view_own') is-invalid @enderror" name="accounting_view_own" id="accounting_view_own" value="true" @if(old('accounting_view_own', optional($permissions)->hasPermissionTo('accounting.view.own'))) checked @endif>
                <label class="custom-control-label" for="accounting_view_own">Eigene Abrechnungen ansehen</label>
            </div>
            <div class="invalid-feedback @error('accounting_view_own') d-block @enderror">
                @error('accounting_view_own')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('accounting_view_other') is-invalid @enderror" name="accounting_view_other" id="accounting_view_other" value="true" @if(old('accounting_view_other', optional($permissions)->hasPermissionTo('accounting.view.other'))) checked @endif>
                <label class="custom-control-label" for="accounting_view_other">Andere Abrechnungen ansehen</label>
            </div>
            <div class="invalid-feedback @error('accounting_view_other') d-block @enderror">
                @error('accounting_view_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('accounting_create') is-invalid @enderror" name="accounting_create" id="accounting_create" value="true" @if(old('accounting_create', optional($permissions)->hasPermissionTo('accounting.create'))) checked @endif>
                <label class="custom-control-label" for="accounting_create">Abrechnungen anlegen</label>
            </div>
            <div class="invalid-feedback @error('accounting_create') d-block @enderror">
                @error('accounting_create')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('accounting_update_own') is-invalid @enderror" name="accounting_update_own" id="accounting_update_own" value="true" @if(old('accounting_update_own', optional($permissions)->hasPermissionTo('accounting.update.own'))) checked @endif>
                <label class="custom-control-label" for="accounting_update_own">Eigene Abrechnungen bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('accounting_update_own') d-block @enderror">
                @error('accounting_update_own')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('accounting_update_other') is-invalid @enderror" name="accounting_update_other" id="accounting_update_other" value="true" @if(old('accounting_update_other', optional($permissions)->hasPermissionTo('accounting.update.other'))) checked @endif>
                <label class="custom-control-label" for="accounting_update_other">Andere Abrechnungen bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('accounting_update_other') d-block @enderror">
                @error('accounting_update_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('accounting_delete_own') is-invalid @enderror" name="accounting_delete_own" id="accounting_delete_own" value="true" @if(old('accounting_delete_own', optional($permissions)->hasPermissionTo('accounting.delete.own'))) checked @endif>
                <label class="custom-control-label" for="accounting_delete_own">Eigene Abrechnungen entfernen</label>
            </div>
            <div class="invalid-feedback @error('accounting_delete_own') d-block @enderror">
                @error('accounting_delete_own')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('accounting_delete_other') is-invalid @enderror" name="accounting_delete_other" id="accounting_delete_other" value="true" @if(old('accounting_delete_other', optional($permissions)->hasPermissionTo('accounting.delete.other'))) checked @endif>
                <label class="custom-control-label" for="accounting_delete_other">Andere Abrechnungen entfernen</label>
            </div>
            <div class="invalid-feedback @error('accounting_delete_other') d-block @enderror">
                @error('accounting_delete_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('accounting_email') is-invalid @enderror" name="accounting_email" id="accounting_email" value="true" @if(old('accounting_email', optional($permissions)->hasPermissionTo('accounting.email'))) checked @endif>
                <label class="custom-control-label" for="accounting_email">Abrechnungen per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('accounting_email') d-block @enderror">
                @error('accounting_email')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('accounting_createpdf') is-invalid @enderror" name="accounting_createpdf" id="accounting_createpdf" value="true" @if(old('accounting_createpdf', optional($permissions)->hasPermissionTo('accounting.createpdf'))) checked @endif>
                <label class="custom-control-label" for="accounting_createpdf">PDF Dateien von Abrechnungen erstellen</label>
            </div>
            <div class="invalid-feedback @error('accounting_createpdf') d-block @enderror">
                @error('accounting_createpdf')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#map-pin"></use>
            </svg>
            Adressen
        </p>
        <p class="text-muted">
            Berechtigungen für die Adressen.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('addresses_view') is-invalid @enderror" name="addresses_view" id="addresses_view" value="true" @if(old('addresses_view', optional($permissions)->hasPermissionTo('addresses.view'))) checked @endif>
                <label class="custom-control-label" for="addresses_view">Adressen ansehen</label>
            </div>
            <div class="invalid-feedback @error('addresses_view') d-block @enderror">
                @error('addresses_view')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('addresses_create') is-invalid @enderror" name="addresses_create" id="addresses_create" value="true" @if(old('addresses_create', optional($permissions)->hasPermissionTo('addresses.create'))) checked @endif>
                <label class="custom-control-label" for="addresses_create">Adressen anlegen</label>
            </div>
            <div class="invalid-feedback @error('addresses_create') d-block @enderror">
                @error('addresses_create')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('addresses_update') is-invalid @enderror" name="addresses_update" id="addresses_update" value="true" @if(old('addresses_update', optional($permissions)->hasPermissionTo('addresses.update'))) checked @endif>
                <label class="custom-control-label" for="addresses_update">Adressen bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('addresses_update') d-block @enderror">
                @error('addresses_update')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('addresses_delete') is-invalid @enderror" name="addresses_delete" id="addresses_delete" value="true" @if(old('addresses_delete', optional($permissions)->hasPermissionTo('addresses.delete'))) checked @endif>
                <label class="custom-control-label" for="addresses_delete">Adressen entfernen</label>
            </div>
            <div class="invalid-feedback @error('addresses_delete') d-block @enderror">
                @error('addresses_delete')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('addresses_email') is-invalid @enderror" name="addresses_email" id="addresses_email" value="true" @if(old('addresses_email', optional($permissions)->hasPermissionTo('addresses.email'))) checked @endif>
                <label class="custom-control-label" for="addresses_email">Adressen per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('addresses_email') d-block @enderror">
                @error('addresses_email')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('addresses_createpdf') is-invalid @enderror" name="addresses_createpdf" id="addresses_createpdf" value="true" @if(old('addresses_createpdf', optional($permissions)->hasPermissionTo('addresses.createpdf'))) checked @endif>
                <label class="custom-control-label" for="addresses_createpdf">PDF Dateien von Adressen erstellen</label>
            </div>
            <div class="invalid-feedback @error('addresses_createpdf') d-block @enderror">
                @error('addresses_createpdf')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#voicemail"></use>
            </svg>
            Aktenvermerke
        </p>
        <p class="text-muted">
            Berechtigungen für die Aktenvermerke.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_view_sender') is-invalid @enderror" name="memos_view_sender" id="memos_view_sender" value="true" @if(old('memos_view_sender', optional($permissions)->hasPermissionTo('memos.view.sender'))) checked @endif>
                <label class="custom-control-label" for="memos_view_sender">Aktenvermerke als Verfasser ansehen</label>
            </div>
            <div class="invalid-feedback @error('memos_view_sender') d-block @enderror">
                @error('memos_view_sender')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_view_recipient') is-invalid @enderror" name="memos_view_recipient" id="memos_view_recipient" value="true" @if(old('memos_view_recipient', optional($permissions)->hasPermissionTo('memos.view.recipient'))) checked @endif>
                <label class="custom-control-label" for="memos_view_recipient">Aktenvermerke als Empfänger ansehen</label>
            </div>
            <div class="invalid-feedback @error('memos_view_recipient') d-block @enderror">
                @error('memos_view_recipient')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_view_present') is-invalid @enderror" name="memos_view_present" id="memos_view_present" value="true" @if(old('memos_view_present', optional($permissions)->hasPermissionTo('memos.view.present'))) checked @endif>
                <label class="custom-control-label" for="memos_view_present">Aktenvermerke als Anwesender ansehen</label>
            </div>
            <div class="invalid-feedback @error('memos_view_present') d-block @enderror">
                @error('memos_view_present')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_view_notified') is-invalid @enderror" name="memos_view_notified" id="memos_view_notified" value="true" @if(old('memos_view_notified', optional($permissions)->hasPermissionTo('memos.view.notified'))) checked @endif>
                <label class="custom-control-label" for="memos_view_notified">Aktenvermerke als Benachrichtigter (im Verteiler) ansehen</label>
            </div>
            <div class="invalid-feedback @error('memos_view_notified') d-block @enderror">
                @error('memos_view_notified')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_view_other') is-invalid @enderror" name="memos_view_other" id="memos_view_other" value="true" @if(old('memos_view_other', optional($permissions)->hasPermissionTo('memos.view.other'))) checked @endif>
                <label class="custom-control-label" for="memos_view_other">Andere Aktenvermerke ansehen</label>
            </div>
            <div class="invalid-feedback @error('memos_view_other') d-block @enderror">
                @error('memos_view_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_create') is-invalid @enderror" name="memos_create" id="memos_create" value="true" @if(old('memos_create', optional($permissions)->hasPermissionTo('memos.create'))) checked @endif>
                <label class="custom-control-label" for="memos_create">Aktenvermerke anlegen</label>
            </div>
            <div class="invalid-feedback @error('memos_create') d-block @enderror">
                @error('memos_create')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_update_sender') is-invalid @enderror" name="memos_update_sender" id="memos_update_sender" value="true" @if(old('memos_update_sender', optional($permissions)->hasPermissionTo('memos.update.sender'))) checked @endif>
                <label class="custom-control-label" for="memos_update_sender">Aktenvermerke als Verfassser bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('memos_update_sender') d-block @enderror">
                @error('memos_update_sender')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_update_recipient') is-invalid @enderror" name="memos_update_recipient" id="memos_update_recipient" value="true" @if(old('memos_update_recipient', optional($permissions)->hasPermissionTo('memos.update.recipient'))) checked @endif>
                <label class="custom-control-label" for="memos_update_recipient">Aktenvermerke als Empfänger bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('memos_update_recipient') d-block @enderror">
                @error('memos_update_recipient')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_update_present') is-invalid @enderror" name="memos_update_present" id="memos_update_present" value="true" @if(old('memos_update_present', optional($permissions)->hasPermissionTo('memos.update.present'))) checked @endif>
                <label class="custom-control-label" for="memos_update_present">Aktenvermerke als Anwesender bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('memos_update_present') d-block @enderror">
                @error('memos_update_present')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_update_notified') is-invalid @enderror" name="memos_update_notified" id="memos_update_notified" value="true" @if(old('memos_update_notified', optional($permissions)->hasPermissionTo('memos.update.notified'))) checked @endif>
                <label class="custom-control-label" for="memos_update_notified">Aktenvermerke als Benachrichtigter (im Verteiler) bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('memos_update_notified') d-block @enderror">
                @error('memos_update_notified')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_update_other') is-invalid @enderror" name="memos_update_other" id="memos_update_other" value="true" @if(old('memos_update_other', optional($permissions)->hasPermissionTo('memos.update.other'))) checked @endif>
                <label class="custom-control-label" for="memos_update_other">Andere Aktenvermerke bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('memos_update_other') d-block @enderror">
                @error('memos_update_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_delete_sender') is-invalid @enderror" name="memos_delete_sender" id="memos_delete_sender" value="true" @if(old('memos_delete_sender', optional($permissions)->hasPermissionTo('memos.delete.sender'))) checked @endif>
                <label class="custom-control-label" for="memos_delete_sender">Aktenvermerke als Verfasser löschen</label>
            </div>
            <div class="invalid-feedback @error('memos_delete_sender') d-block @enderror">
                @error('memos_delete_sender')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_delete_recipient') is-invalid @enderror" name="memos_delete_recipient" id="memos_delete_recipient" value="true" @if(old('memos_delete_recipient', optional($permissions)->hasPermissionTo('memos.delete.recipient'))) checked @endif>
                <label class="custom-control-label" for="memos_delete_recipient">Aktenvermerke als Empfänger löschen</label>
            </div>
            <div class="invalid-feedback @error('memos_delete_recipient') d-block @enderror">
                @error('memos_delete_recipient')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_delete_present') is-invalid @enderror" name="memos_delete_present" id="memos_delete_present" value="true" @if(old('memos_delete_present', optional($permissions)->hasPermissionTo('memos.delete.present'))) checked @endif>
                <label class="custom-control-label" for="memos_delete_present">Aktenvermerke als Anwesender löschen</label>
            </div>
            <div class="invalid-feedback @error('memos_delete_present') d-block @enderror">
                @error('memos_delete_present')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_delete_notified') is-invalid @enderror" name="memos_delete_notified" id="memos_delete_notified" value="true" @if(old('memos_delete_notified', optional($permissions)->hasPermissionTo('memos.delete.notified'))) checked @endif>
                <label class="custom-control-label" for="memos_delete_notified">Aktenvermerke als Benachrichtiger (im Verteiler) löschen</label>
            </div>
            <div class="invalid-feedback @error('memos_delete_notified') d-block @enderror">
                @error('memos_delete_notified')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_delete_other') is-invalid @enderror" name="memos_delete_other" id="memos_delete_other" value="true" @if(old('memos_delete_other', optional($permissions)->hasPermissionTo('memos.delete.other'))) checked @endif>
                <label class="custom-control-label" for="memos_delete_other">Andere Aktenvermerke löschen</label>
            </div>
            <div class="invalid-feedback @error('memos_delete_other') d-block @enderror">
                @error('memos_delete_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_email_sender') is-invalid @enderror" name="memos_email_sender" id="memos_email_sender" value="true" @if(old('memos_email_sender', optional($permissions)->hasPermissionTo('memos.email.sender'))) checked @endif>
                <label class="custom-control-label" for="memos_email_sender">Aktenvermerke als Verfasser per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('memos_email_sender') d-block @enderror">
                @error('memos_email_sender')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_email_recipient') is-invalid @enderror" name="memos_email_recipient" id="memos_email_recipient" value="true" @if(old('memos_email_recipient', optional($permissions)->hasPermissionTo('memos.email.recipient'))) checked @endif>
                <label class="custom-control-label" for="memos_email_recipient">Aktenvermerke als Empfänger per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('memos_email_recipient') d-block @enderror">
                @error('memos_email_recipient')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_email_present') is-invalid @enderror" name="memos_email_present" id="memos_email_present" value="true" @if(old('memos_email_present', optional($permissions)->hasPermissionTo('memos.email.present'))) checked @endif>
                <label class="custom-control-label" for="memos_email_present">Aktenvermerke als Anwesender per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('memos_email_present') d-block @enderror">
                @error('memos_email_present')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_email_notified') is-invalid @enderror" name="memos_email_notified" id="memos_email_notified" value="true" @if(old('memos_email_notified', optional($permissions)->hasPermissionTo('memos.email.notified'))) checked @endif>
                <label class="custom-control-label" for="memos_email_notified">Aktenvermerke als Benachrichtigter (im Verteiler) per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('memos_email_notified') d-block @enderror">
                @error('memos_email_notified')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_email_other') is-invalid @enderror" name="memos_email_other" id="memos_email_other" value="true" @if(old('memos_email_other', optional($permissions)->hasPermissionTo('memos.email.other'))) checked @endif>
                <label class="custom-control-label" for="memos_email_other">Andere Aktenvermerke per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('memos_email_other') d-block @enderror">
                @error('memos_email_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_createpdf_sender') is-invalid @enderror" name="memos_createpdf_sender" id="memos_createpdf_sender" value="true" @if(old('memos_createpdf_sender', optional($permissions)->hasPermissionTo('memos.createpdf.sender'))) checked @endif>
                <label class="custom-control-label" for="memos_createpdf_sender">PDF Dateien von Aktenvermerken als Verfasser erstellen</label>
            </div>
            <div class="invalid-feedback @error('memos_createpdf_sender') d-block @enderror">
                @error('memos_createpdf_sender')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_createpdf_recipient') is-invalid @enderror" name="memos_createpdf_recipient" id="memos_createpdf_recipient" value="true" @if(old('memos_createpdf_recipient', optional($permissions)->hasPermissionTo('memos.createpdf.recipient'))) checked @endif>
                <label class="custom-control-label" for="memos_createpdf_recipient">PDF Dateien von Aktenvermerken als Empfänger erstellen</label>
            </div>
            <div class="invalid-feedback @error('memos_createpdf_recipient') d-block @enderror">
                @error('memos_createpdf_recipient')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_createpdf_present') is-invalid @enderror" name="memos_createpdf_present" id="memos_createpdf_present" value="true" @if(old('memos_createpdf_present', optional($permissions)->hasPermissionTo('memos.createpdf.present'))) checked @endif>
                <label class="custom-control-label" for="memos_createpdf_present">PDF Dateien von Aktenvermerken als Anwesender erstellen</label>
            </div>
            <div class="invalid-feedback @error('memos_createpdf_present') d-block @enderror">
                @error('memos_createpdf_present')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_createpdf_notified') is-invalid @enderror" name="memos_createpdf_notified" id="memos_createpdf_notified" value="true" @if(old('memos_createpdf_notified', optional($permissions)->hasPermissionTo('memos.createpdf.notified'))) checked @endif>
                <label class="custom-control-label" for="memos_createpdf_notified">PDF Dateien von Aktenvermerken als Benachrichtigter (im Verteiler) erstellen</label>
            </div>
            <div class="invalid-feedback @error('memos_createpdf_notified') d-block @enderror">
                @error('memos_createpdf_notified')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('memos_createpdf_other') is-invalid @enderror" name="memos_createpdf_other" id="memos_createpdf_other" value="true" @if(old('memos_createpdf_other', optional($permissions)->hasPermissionTo('memos.createpdf.other'))) checked @endif>
                <label class="custom-control-label" for="memos_createpdf_other">PDF Dateien von anderen Aktenvermerken erstellen</label>
            </div>
            <div class="invalid-feedback @error('memos_createpdf_other') d-block @enderror">
                @error('memos_createpdf_other')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
            </svg>
            Applikationseinstellungen
        </p>
        <p class="text-muted">
            Berechtigungen für die Applikationseinstellungen.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('application-settings_update_general') is-invalid @enderror" name="application-settings_update_general" id="application-settings_update_general" value="true" @if(old('application-settings_update_general', optional($permissions)->hasPermissionTo('application-settings.update.general'))) checked @endif>
                <label class="custom-control-label" for="application-settings_update_general">Allgemeine Applikationseinstellungen bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('application-settings_update_general') d-block @enderror">
                @error('application-settings_update_general')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
            </svg>
            Aufgaben
        </p>
        <p class="text-muted">
            Berechtigungen für die Aufgaben.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_view_responsible') is-invalid @enderror" name="tasks_view_responsible" id="tasks_view_responsible" value="true" @if(old('tasks_view_responsible', optional($permissions)->hasPermissionTo('tasks.view.responsible'))) checked @endif>
                <label class="custom-control-label" for="tasks_view_responsible">Aufgaben als Verantortlicher ansehen</label>
            </div>
            <div class="invalid-feedback @error('tasks_view_responsible') d-block @enderror">
                @error('tasks_view_responsible')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_view_involved') is-invalid @enderror" name="tasks_view_involved" id="tasks_view_involved" value="true" @if(old('tasks_view_involved', optional($permissions)->hasPermissionTo('tasks.view.involved'))) checked @endif>
                <label class="custom-control-label" for="tasks_view_involved">Aufgaben als Beteiliger ansehen</label>
            </div>
            <div class="invalid-feedback @error('tasks_view_involved') d-block @enderror">
                @error('tasks_view_involved')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_view_other') is-invalid @enderror" name="tasks_view_other" id="tasks_view_other" value="true" @if(old('tasks_view_other', optional($permissions)->hasPermissionTo('tasks.view.other'))) checked @endif>
                <label class="custom-control-label" for="tasks_view_other">Andere Aufgaben ansehen</label>
            </div>
            <div class="invalid-feedback @error('tasks_view_other') d-block @enderror">
                @error('tasks_view_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_view_private_responsible') is-invalid @enderror" name="tasks_view_private_responsible" id="tasks_view_private_responsible" value="true" @if(old('tasks_view_private_responsible', optional($permissions)->hasPermissionTo('tasks.view.private.responsible'))) checked @endif>
                <label class="custom-control-label" for="tasks_view_private_responsible">Private Aufgaben als Verantwortlicher ansehen</label>
            </div>
            <div class="invalid-feedback @error('tasks_view_private_responsible') d-block @enderror">
                @error('tasks_view_private_responsible')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_view_private_involved') is-invalid @enderror" name="tasks_view_private_involved" id="tasks_view_private_involved" value="true" @if(old('tasks_view_private_involved', optional($permissions)->hasPermissionTo('tasks.view.private.involved'))) checked @endif>
                <label class="custom-control-label" for="tasks_view_private_involved">Private Aufgaben als Beteiligter ansehen</label>
            </div>
            <div class="invalid-feedback @error('tasks_view_private_involved') d-block @enderror">
                @error('tasks_view_private_involved')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_view_private_other') is-invalid @enderror" name="tasks_view_private_other" id="tasks_view_private_other" value="true" @if(old('tasks_view_private_other', optional($permissions)->hasPermissionTo('tasks.view.private.other'))) checked @endif>
                <label class="custom-control-label" for="tasks_view_private_other">Andere private Aufgaben ansehen</label>
            </div>
            <div class="invalid-feedback @error('tasks_view_private_other') d-block @enderror">
                @error('tasks_view_private_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_create') is-invalid @enderror" name="tasks_create" id="tasks_create" value="true" @if(old('tasks_create', optional($permissions)->hasPermissionTo('tasks.create'))) checked @endif>
                <label class="custom-control-label" for="tasks_create">Aufgaben anlegen</label>
            </div>
            <div class="invalid-feedback @error('tasks_create') d-block @enderror">
                @error('tasks_create')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_create_private') is-invalid @enderror" name="tasks_create_private" id="tasks_create_private" value="true" @if(old('tasks_create_private', optional($permissions)->hasPermissionTo('tasks.create.private'))) checked @endif>
                <label class="custom-control-label" for="tasks_create_private">Private Aufgaben anlegen</label>
            </div>
            <div class="invalid-feedback @error('tasks_create_private') d-block @enderror">
                @error('tasks_create_private')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_update_responsible') is-invalid @enderror" name="tasks_update_responsible" id="tasks_update_responsible" value="true" @if(old('tasks_update_responsible', optional($permissions)->hasPermissionTo('tasks.update.responsible'))) checked @endif>
                <label class="custom-control-label" for="tasks_update_responsible">Aufgaben als Verantwortlicher bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('tasks_update_responsible') d-block @enderror">
                @error('tasks_update_responsible')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_update_involved') is-invalid @enderror" name="tasks_update_involved" id="tasks_update_involved" value="true" @if(old('tasks_update_involved', optional($permissions)->hasPermissionTo('tasks.update.involved'))) checked @endif>
                <label class="custom-control-label" for="tasks_update_involved">Aufgaben als Beteiligter barbeiten</label>
            </div>
            <div class="invalid-feedback @error('tasks_update_involved') d-block @enderror">
                @error('tasks_update_involved')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_update_other') is-invalid @enderror" name="tasks_update_other" id="tasks_update_other" value="true" @if(old('tasks_update_other', optional($permissions)->hasPermissionTo('tasks.update.other'))) checked @endif>
                <label class="custom-control-label" for="tasks_update_other">Andere Aufgaben bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('tasks_update_other') d-block @enderror">
                @error('tasks_update_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_update_private_responsible') is-invalid @enderror" name="tasks_update_private_responsible" id="tasks_update_private_responsible" value="true" @if(old('tasks_update_private_responsible', optional($permissions)->hasPermissionTo('tasks.update.private.responsible'))) checked @endif>
                <label class="custom-control-label" for="tasks_update_private_responsible">Private Aufgaben als Verantwortlicher bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('tasks_update_private_responsible') d-block @enderror">
                @error('tasks_update_private_responsible')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_update_private_involved') is-invalid @enderror" name="tasks_update_private_involved" id="tasks_update_private_involved" value="true" @if(old('tasks_update_private_involved', optional($permissions)->hasPermissionTo('tasks.update.private.involved'))) checked @endif>
                <label class="custom-control-label" for="tasks_update_private_involved">Private Aufgaben als Beteiligter bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('tasks_update_private_involved') d-block @enderror">
                @error('tasks_update_private_involved')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_update_private_other') is-invalid @enderror" name="tasks_update_private_other" id="tasks_update_private_other" value="true" @if(old('tasks_update_private_other', optional($permissions)->hasPermissionTo('tasks.update.private.other'))) checked @endif>
                <label class="custom-control-label" for="tasks_update_private_other">Andere private Aufgaben bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('tasks_update_private_other') d-block @enderror">
                @error('tasks_update_private_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_delete_responsible') is-invalid @enderror" name="tasks_delete_responsible" id="tasks_delete_responsible" value="true" @if(old('tasks_delete_responsible', optional($permissions)->hasPermissionTo('tasks.delete.responsible'))) checked @endif>
                <label class="custom-control-label" for="tasks_delete_responsible">Aufgaben als Verantwortlicher entfernen</label>
            </div>
            <div class="invalid-feedback @error('tasks_delete_responsible') d-block @enderror">
                @error('tasks_delete_responsible')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_delete_involved') is-invalid @enderror" name="tasks_delete_involved" id="tasks_delete_involved" value="true" @if(old('tasks_delete_involved', optional($permissions)->hasPermissionTo('tasks.delete.involved'))) checked @endif>
                <label class="custom-control-label" for="tasks_delete_involved">Aufgaben als Beteiligter entfernen</label>
            </div>
            <div class="invalid-feedback @error('tasks_delete_involved') d-block @enderror">
                @error('tasks_delete_involved')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_delete_other') is-invalid @enderror" name="tasks_delete_other" id="tasks_delete_other" value="true" @if(old('tasks_delete_other', optional($permissions)->hasPermissionTo('tasks.delete.other'))) checked @endif>
                <label class="custom-control-label" for="tasks_delete_other">Andere Aufgaben entfernen</label>
            </div>
            <div class="invalid-feedback @error('tasks_delete_other') d-block @enderror">
                @error('tasks_delete_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_delete_private_responsible') is-invalid @enderror" name="tasks_delete_private_responsible" id="tasks_delete_private_responsible" value="true" @if(old('tasks_delete_private_responsible', optional($permissions)->hasPermissionTo('tasks.delete.private.responsible'))) checked @endif>
                <label class="custom-control-label" for="tasks_delete_private_responsible">Private Aufgaben als Verantwortlicher entfernen</label>
            </div>
            <div class="invalid-feedback @error('tasks_delete_private_responsible') d-block @enderror">
                @error('tasks_delete_private_responsible')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_delete_private_involved') is-invalid @enderror" name="tasks_delete_private_involved" id="tasks_delete_private_involved" value="true" @if(old('tasks_delete_private_involved', optional($permissions)->hasPermissionTo('tasks.delete.private.involved'))) checked @endif>
                <label class="custom-control-label" for="tasks_delete_private_involved">Private Aufgaben als Beteiligter entfernen</label>
            </div>
            <div class="invalid-feedback @error('tasks_delete_private_involved') d-block @enderror">
                @error('tasks_delete_private_involved')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_delete_private_other') is-invalid @enderror" name="tasks_delete_private_other" id="tasks_delete_private_other" value="true" @if(old('tasks_delete_private_other', optional($permissions)->hasPermissionTo('tasks.delete.private.other'))) checked @endif>
                <label class="custom-control-label" for="tasks_delete_private_other">Andere private Aufgaben entfernen</label>
            </div>
            <div class="invalid-feedback @error('tasks_delete_private_other') d-block @enderror">
                @error('tasks_delete_private_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_email_responsible') is-invalid @enderror" name="tasks_email_responsible" id="tasks_email_responsible" value="true" @if(old('tasks_email_responsible', optional($permissions)->hasPermissionTo('tasks.email.responsible'))) checked @endif>
                <label class="custom-control-label" for="tasks_email_responsible">Aufgaben als Verantwortlicher per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('tasks_email_responsible') d-block @enderror">
                @error('tasks_email_responsible')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_email_involved') is-invalid @enderror" name="tasks_email_involved" id="tasks_email_involved" value="true" @if(old('tasks_email_involved', optional($permissions)->hasPermissionTo('tasks.email.involved'))) checked @endif>
                <label class="custom-control-label" for="tasks_email_involved">Aufgaben als Beteiligter per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('tasks_email_involved') d-block @enderror">
                @error('tasks_email_involved')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_email_other') is-invalid @enderror" name="tasks_email_other" id="tasks_email_other" value="true" @if(old('tasks_email_other', optional($permissions)->hasPermissionTo('tasks.email.other'))) checked @endif>
                <label class="custom-control-label" for="tasks_email_other">Andere Aufgaben per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('tasks_email_other') d-block @enderror">
                @error('tasks_email_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_email_private_responsible') is-invalid @enderror" name="tasks_email_private_responsible" id="tasks_email_private_responsible" value="true" @if(old('tasks_email_private_responsible', optional($permissions)->hasPermissionTo('tasks.email.private.responsible'))) checked @endif>
                <label class="custom-control-label" for="tasks_email_private_responsible">Private Aufgaben als Verantwortlicher per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('tasks_email_private_responsible') d-block @enderror">
                @error('tasks_email_private_responsible')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_email_private_involved') is-invalid @enderror" name="tasks_email_private_involved" id="tasks_email_private_involved" value="true" @if(old('tasks_email_private_involved', optional($permissions)->hasPermissionTo('tasks.email.private.involved'))) checked @endif>
                <label class="custom-control-label" for="tasks_email_private_involved">Private Aufgaben als Beteiligter per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('tasks_email_private_involved') d-block @enderror">
                @error('tasks_email_private_involved')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_email_private_other') is-invalid @enderror" name="tasks_email_private_other" id="tasks_email_private_other" value="true" @if(old('tasks_email_private_other', optional($permissions)->hasPermissionTo('tasks.email.private.other'))) checked @endif>
                <label class="custom-control-label" for="tasks_email_private_other">Andere private Aufgaben per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('tasks_email_private_other') d-block @enderror">
                @error('tasks_email_private_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_createpdf_responsible') is-invalid @enderror" name="tasks_createpdf_responsible" id="tasks_createpdf_responsible" value="true" @if(old('tasks_createpdf_responsible', optional($permissions)->hasPermissionTo('tasks.createpdf.responsible'))) checked @endif>
                <label class="custom-control-label" for="tasks_createpdf_responsible">PDF Dateien von Aufgaben als Verantwortlicher erstellen</label>
            </div>
            <div class="invalid-feedback @error('tasks_createpdf_responsible') d-block @enderror">
                @error('tasks_createpdf_responsible')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_createpdf_involved') is-invalid @enderror" name="tasks_createpdf_involved" id="tasks_createpdf_involved" value="true" @if(old('tasks_createpdf_involved', optional($permissions)->hasPermissionTo('tasks.createpdf.involved'))) checked @endif>
                <label class="custom-control-label" for="tasks_createpdf_involved">PDF Dateien von Aufgaben als Beteiligter erstellen</label>
            </div>
            <div class="invalid-feedback @error('tasks_createpdf_involved') d-block @enderror">
                @error('tasks_createpdf_involved')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_createpdf_other') is-invalid @enderror" name="tasks_createpdf_other" id="tasks_createpdf_other" value="true" @if(old('tasks_createpdf_other', optional($permissions)->hasPermissionTo('tasks.createpdf.other'))) checked @endif>
                <label class="custom-control-label" for="tasks_createpdf_other">PDF Dateien von anderen Aufgaben erstellen</label>
            </div>
            <div class="invalid-feedback @error('tasks_createpdf_other') d-block @enderror">
                @error('tasks_createpdf_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_createpdf_private_responsible') is-invalid @enderror" name="tasks_createpdf_private_responsible" id="tasks_createpdf_private_responsible" value="true" @if(old('tasks_createpdf_private_responsible', optional($permissions)->hasPermissionTo('tasks.createpdf.private.responsible'))) checked @endif>
                <label class="custom-control-label" for="tasks_createpdf_private_responsible">PDF Dateien von privaten Aufgaben als Verantwortlicher erstellen</label>
            </div>
            <div class="invalid-feedback @error('tasks_createpdf_private_responsible') d-block @enderror">
                @error('tasks_createpdf_private_responsible')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_createpdf_private_involved') is-invalid @enderror" name="tasks_createpdf_private_involved" id="tasks_createpdf_private_involved" value="true" @if(old('tasks_createpdf_private_involved', optional($permissions)->hasPermissionTo('tasks.createpdf.private.involved'))) checked @endif>
                <label class="custom-control-label" for="tasks_createpdf_private_involved">PDF Dateien von privaten Aufgaben als Beteiligter erstellen</label>
            </div>
            <div class="invalid-feedback @error('tasks_createpdf_private_involved') d-block @enderror">
                @error('tasks_createpdf_private_involved')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tasks_createpdf_private_other') is-invalid @enderror" name="tasks_createpdf_private_other" id="tasks_createpdf_private_other" value="true" @if(old('tasks_createpdf_private_other', optional($permissions)->hasPermissionTo('tasks.createpdf.private.other'))) checked @endif>
                <label class="custom-control-label" for="tasks_createpdf_private_other">PDF Dateien von anderen privaten Aufgaben erstellen</label>
            </div>
            <div class="invalid-feedback @error('tasks_createpdf_private_other') d-block @enderror">
                @error('tasks_createpdf_private_other')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#book"></use>
            </svg>
            Fahrtenbuch
        </p>
        <p class="text-muted">
            Berechtigungen für die Fahrtenbuch.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('logbook_view_own') is-invalid @enderror" name="logbook_view_own" id="logbook_view_own" value="true" @if(old('logbook_view_own', optional($permissions)->hasPermissionTo('logbook.view.own'))) checked @endif>
                <label class="custom-control-label" for="logbook_view_own">Eigene Fahrten anzeigen</label>
            </div>
            <div class="invalid-feedback @error('logbook_view_own') d-block @enderror">
                @error('logbook_view_own')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('logbook_view_other') is-invalid @enderror" name="logbook_view_other" id="logbook_view_other" value="true" @if(old('logbook_view_other', optional($permissions)->hasPermissionTo('logbook.view.other'))) checked @endif>
                <label class="custom-control-label" for="logbook_view_other">Andere Fahrten anzeigen</label>
            </div>
            <div class="invalid-feedback @error('logbook_view_other') d-block @enderror">
                @error('logbook_view_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('logbook_create') is-invalid @enderror" name="logbook_create" id="logbook_create" value="true" @if(old('logbook_create', optional($permissions)->hasPermissionTo('logbook.create'))) checked @endif>
                <label class="custom-control-label" for="logbook_create">Fahrten anlegen</label>
            </div>
            <div class="invalid-feedback @error('logbook_create') d-block @enderror">
                @error('logbook_create')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('logbook_update_own') is-invalid @enderror" name="logbook_update_own" id="logbook_update_own" value="true" @if(old('logbook_update_own', optional($permissions)->hasPermissionTo('logbook.update.own'))) checked @endif>
                <label class="custom-control-label" for="logbook_update_own">Eigene Fahrten bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('logbook_update_own') d-block @enderror">
                @error('logbook_update_own')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('logbook_update_other') is-invalid @enderror" name="logbook_update_other" id="logbook_update_other" value="true" @if(old('logbook_update_other', optional($permissions)->hasPermissionTo('logbook.update.other'))) checked @endif>
                <label class="custom-control-label" for="logbook_update_other">Andere Fahrten bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('logbook_update_other') d-block @enderror">
                @error('logbook_update_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('logbook_delete_own') is-invalid @enderror" name="logbook_delete_own" id="logbook_delete_own" value="true" @if(old('logbook_delete_own', optional($permissions)->hasPermissionTo('logbook.delete.own'))) checked @endif>
                <label class="custom-control-label" for="logbook_delete_own">Eigene Fahrten löschen</label>
            </div>
            <div class="invalid-feedback @error('logbook_delete_own') d-block @enderror">
                @error('logbook_delete_own')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('logbook_delete_other') is-invalid @enderror" name="logbook_delete_other" id="logbook_delete_other" value="true" @if(old('logbook_delete_other', optional($permissions)->hasPermissionTo('logbook.delete.other'))) checked @endif>
                <label class="custom-control-label" for="logbook_delete_other">Andere Fahrten löschen</label>
            </div>
            <div class="invalid-feedback @error('logbook_delete_other') d-block @enderror">
                @error('logbook_delete_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('logbook_email') is-invalid @enderror" name="logbook_email" id="logbook_email" value="true" @if(old('logbook_email', optional($permissions)->hasPermissionTo('logbook.email'))) checked @endif>
                <label class="custom-control-label" for="logbook_email">Fahrten per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('logbook_email') d-block @enderror">
                @error('logbook_email')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('logbook_createpdf') is-invalid @enderror" name="logbook_createpdf" id="logbook_createpdf" value="true" @if(old('logbook_createpdf', optional($permissions)->hasPermissionTo('logbook.createpdf'))) checked @endif>
                <label class="custom-control-label" for="logbook_createpdf">PDF Dateien von Fahrten erstellen</label>
            </div>
            <div class="invalid-feedback @error('logbook_createpdf') d-block @enderror">
                @error('logbook_createpdf')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#truck"></use>
            </svg>
            Fahrzeuge
        </p>
        <p class="text-muted">
            Berechtigungen für die Fahrzeuge.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('vehicles_view') is-invalid @enderror" name="vehicles_view" id="vehicles_view" value="true" @if(old('vehicles_view', optional($permissions)->hasPermissionTo('vehicles.view'))) checked @endif>
                <label class="custom-control-label" for="vehicles_view">Fahrzeuge ansehen</label>
            </div>
            <div class="invalid-feedback @error('vehicles_view') d-block @enderror">
                @error('vehicles_view')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('vehicles_create') is-invalid @enderror" name="vehicles_create" id="vehicles_create" value="true" @if(old('vehicles_create', optional($permissions)->hasPermissionTo('vehicles.create'))) checked @endif>
                <label class="custom-control-label" for="vehicles_create">Fahrzeuge anlegen</label>
            </div>
            <div class="invalid-feedback @error('vehicles_create') d-block @enderror">
                @error('vehicles_create')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('vehicles_update') is-invalid @enderror" name="vehicles_update" id="vehicles_update" value="true" @if(old('vehicles_update', optional($permissions)->hasPermissionTo('vehicles.update'))) checked @endif>
                <label class="custom-control-label" for="vehicles_update">Fahrzeuge bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('vehicles_update') d-block @enderror">
                @error('vehicles_update')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('vehicles_delete') is-invalid @enderror" name="vehicles_delete" id="vehicles_delete" value="true" @if(old('vehicles_delete', optional($permissions)->hasPermissionTo('vehicles.delete'))) checked @endif>
                <label class="custom-control-label" for="vehicles_delete">Fahrzeuge entfernen</label>
            </div>
            <div class="invalid-feedback @error('vehicles_delete') d-block @enderror">
                @error('vehicles_delete')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('vehicles_email') is-invalid @enderror" name="vehicles_email" id="vehicles_email" value="true" @if(old('vehicles_email', optional($permissions)->hasPermissionTo('vehicles.email'))) checked @endif>
                <label class="custom-control-label" for="vehicles_email">Fahrzeuge per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('vehicles_email') d-block @enderror">
                @error('vehicles_email')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('vehicles_createpdf') is-invalid @enderror" name="vehicles_createpdf" id="vehicles_createpdf" value="true" @if(old('vehicles_createpdf', optional($permissions)->hasPermissionTo('vehicles.createpdf'))) checked @endif>
                <label class="custom-control-label" for="vehicles_createpdf">PDF Dateien von Fahrzeugen erstellen</label>
            </div>
            <div class="invalid-feedback @error('vehicles_createpdf') d-block @enderror">
                @error('vehicles_createpdf')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
            </svg>
            Firmen
        </p>
        <p class="text-muted">
            Berechtigungen für die Firmen.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('companies_view') is-invalid @enderror" name="companies_view" id="companies_view" value="true" @if(old('companies_view', optional($permissions)->hasPermissionTo('companies.view'))) checked @endif>
                <label class="custom-control-label" for="companies_view">Firmen ansehen</label>
            </div>
            <div class="invalid-feedback @error('companies_view') d-block @enderror">
                @error('companies_view')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('companies_create') is-invalid @enderror" name="companies_create" id="companies_create" value="true" @if(old('companies_create', optional($permissions)->hasPermissionTo('companies.create'))) checked @endif>
                <label class="custom-control-label" for="companies_create">Firmen anlegen</label>
            </div>
            <div class="invalid-feedback @error('companies_create') d-block @enderror">
                @error('companies_create')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('companies_update') is-invalid @enderror" name="companies_update" id="companies_update" value="true" @if(old('companies_update', optional($permissions)->hasPermissionTo('companies.update'))) checked @endif>
                <label class="custom-control-label" for="companies_update">Firmen bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('companies_update') d-block @enderror">
                @error('companies_update')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('companies_delete') is-invalid @enderror" name="companies_delete" id="companies_delete" value="true" @if(old('companies_delete', optional($permissions)->hasPermissionTo('companies.delete'))) checked @endif>
                <label class="custom-control-label" for="companies_delete">Firmen entfernen</label>
            </div>
            <div class="invalid-feedback @error('companies_delete') d-block @enderror">
                @error('companies_delete')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('companies_email') is-invalid @enderror" name="companies_email" id="companies_email" value="true" @if(old('companies_email', optional($permissions)->hasPermissionTo('companies.email'))) checked @endif>
                <label class="custom-control-label" for="companies_email">Firmen per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('companies_email') d-block @enderror">
                @error('companies_email')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('companies_createpdf') is-invalid @enderror" name="companies_createpdf" id="companies_createpdf" value="true" @if(old('companies_createpdf', optional($permissions)->hasPermissionTo('companies.createpdf'))) checked @endif>
                <label class="custom-control-label" for="companies_createpdf">PDF Dateien von Firmen erstellen</label>
            </div>
            <div class="invalid-feedback @error('companies_createpdf') d-block @enderror">
                @error('companies_createpdf')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#help-circle"></use>
            </svg>
            Hilfe
        </p>
        <p class="text-muted">
            Berechtigungen für die Hilfe.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('help_view') is-invalid @enderror" name="help_view" id="help_view" value="true" @if(old('help_view', optional($permissions)->hasPermissionTo('help.view'))) checked @endif>
                <label class="custom-control-label" for="help_view">Hilfe ansehen</label>
            </div>
            <div class="invalid-feedback @error('help_view') d-block @enderror">
                @error('help_view')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#cpu"></use>
            </svg>
            Lohndienstleistungen
        </p>
        <p class="text-muted">
            Berechtigungen für die Lohndienstleistungen.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('wage-services_view') is-invalid @enderror" name="wage-services_view" id="wage-services_view" value="true" @if(old('wage-services_view', optional($permissions)->hasPermissionTo('wage-services.view'))) checked @endif>
                <label class="custom-control-label" for="wage-services_view">Lohndienstleistungen ansehen</label>
            </div>
            <div class="invalid-feedback @error('wage-services_view') d-block @enderror">
                @error('wage-services_view')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('wage-services_create') is-invalid @enderror" name="wage-services_create" id="wage-services_create" value="true" @if(old('wage-services_create', optional($permissions)->hasPermissionTo('wage-services.create'))) checked @endif>
                <label class="custom-control-label" for="wage-services_create">Lohndienstleistungen anlegen</label>
            </div>
            <div class="invalid-feedback @error('wage-services_create') d-block @enderror">
                @error('wage-services_create')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('wage-services_update') is-invalid @enderror" name="wage-services_update" id="wage-services_update" value="true" @if(old('wage-services_update', optional($permissions)->hasPermissionTo('wage-services.update'))) checked @endif>
                <label class="custom-control-label" for="wage-services_update">Lohndienstleistungen bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('wage-services_update') d-block @enderror">
                @error('wage-services_update')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('wage-services_delete') is-invalid @enderror" name="wage-services_delete" id="wage-services_delete" value="true" @if(old('wage-services_delete', optional($permissions)->hasPermissionTo('wage-services.delete'))) checked @endif>
                <label class="custom-control-label" for="wage-services_delete">Lohndienstleistungen entfernen</label>
            </div>
            <div class="invalid-feedback @error('wage-services_delete') d-block @enderror">
                @error('wage-services_delete')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('wage-services_email') is-invalid @enderror" name="wage-services_email" id="wage-services_email" value="true" @if(old('wage-services_email', optional($permissions)->hasPermissionTo('wage-services.email'))) checked @endif>
                <label class="custom-control-label" for="wage-services_email">Lohndienstleistungen per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('wage-services_email') d-block @enderror">
                @error('wage-services_email')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('wage-services_createpdf') is-invalid @enderror" name="wage-services_createpdf" id="wage-services_createpdf" value="true" @if(old('wage-services_createpdf', optional($permissions)->hasPermissionTo('wage-services.createpdf'))) checked @endif>
                <label class="custom-control-label" for="wage-services_createpdf">PDF Dateien von Lohndienstleistungen erstellen</label>
            </div>
            <div class="invalid-feedback @error('wage-services_createpdf') d-block @enderror">
                @error('wage-services_createpdf')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#box"></use>
            </svg>
            Materialleistungen
        </p>
        <p class="text-muted">
            Berechtigungen für die Materialleistungen.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('material-services_view') is-invalid @enderror" name="material-services_view" id="material-services_view" value="true" @if(old('material-services_view', optional($permissions)->hasPermissionTo('material-services.view'))) checked @endif>
                <label class="custom-control-label" for="material-services_view">Materialleistungen ansehen</label>
            </div>
            <div class="invalid-feedback @error('material-services_view') d-block @enderror">
                @error('material-services_view')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('material-services_create') is-invalid @enderror" name="material-services_create" id="material-services_create" value="true" @if(old('material-services_create', optional($permissions)->hasPermissionTo('material-services.create'))) checked @endif>
                <label class="custom-control-label" for="material-services_create">Materialleistungen anlegen</label>
            </div>
            <div class="invalid-feedback @error('material-services_create') d-block @enderror">
                @error('material-services_create')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('material-services_update') is-invalid @enderror" name="material-services_update" id="material-services_update" value="true" @if(old('material-services_update', optional($permissions)->hasPermissionTo('material-services.update'))) checked @endif>
                <label class="custom-control-label" for="material-services_update">Materialleistungen bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('material-services_update') d-block @enderror">
                @error('material-services_update')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('material-services_delete') is-invalid @enderror" name="material-services_delete" id="material-services_delete" value="true" @if(old('material-services_delete', optional($permissions)->hasPermissionTo('material-services.delete'))) checked @endif>
                <label class="custom-control-label" for="material-services_delete">Materialleistungen löschen</label>
            </div>
            <div class="invalid-feedback @error('material-services_delete') d-block @enderror">
                @error('material-services_delete')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('material-services_email') is-invalid @enderror" name="material-services_email" id="material-services_email" value="true" @if(old('material-services_email', optional($permissions)->hasPermissionTo('material-services.email'))) checked @endif>
                <label class="custom-control-label" for="material-services_email">Materialleistungen per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('material-services_email') d-block @enderror">
                @error('material-services_email')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('material-services_createpdf') is-invalid @enderror" name="material-services_createpdf" id="material-services_createpdf" value="true" @if(old('material-services_createpdf', optional($permissions)->hasPermissionTo('material-services.createpdf'))) checked @endif>
                <label class="custom-control-label" for="material-services_createpdf">PDF Dateien von Materialleistungen erstellen</label>
            </div>
            <div class="invalid-feedback @error('material-services_createpdf') d-block @enderror">
                @error('material-services_createpdf')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
            </svg>
            Mitarbeiter
        </p>
        <p class="text-muted">
            Berechtigungen für die Mitarbeiter.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('employees_view') is-invalid @enderror" name="employees_view" id="employees_view" value="true" @if(old('employees_view', optional($permissions)->hasPermissionTo('employees.view'))) checked @endif>
                <label class="custom-control-label" for="employees_view">Mitarbeiter ansehen</label>
            </div>
            <div class="invalid-feedback @error('employees_view') d-block @enderror">
                @error('employees_view')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('employees_create') is-invalid @enderror" name="employees_create" id="employees_create" value="true" @if(old('employees_create', optional($permissions)->hasPermissionTo('employees.create'))) checked @endif>
                <label class="custom-control-label" for="employees_create">Mitarbeiter anlegen</label>
            </div>
            <div class="invalid-feedback @error('employees_create') d-block @enderror">
                @error('employees_create')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('employees_update') is-invalid @enderror" name="employees_update" id="employees_update" value="true" @if(old('employees_update', optional($permissions)->hasPermissionTo('employees.update'))) checked @endif>
                <label class="custom-control-label" for="employees_update">Mitarbeiter bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('employees_update') d-block @enderror">
                @error('employees_update')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('employees_delete') is-invalid @enderror" name="employees_delete" id="employees_delete" value="true" @if(old('employees_delete', optional($permissions)->hasPermissionTo('employees.delete'))) checked @endif>
                <label class="custom-control-label" for="employees_delete">Mitarbeiter entfernen</label>
            </div>
            <div class="invalid-feedback @error('employees_delete') d-block @enderror">
                @error('employees_delete')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('employees_email') is-invalid @enderror" name="employees_email" id="employees_email" value="true" @if(old('employees_email', optional($permissions)->hasPermissionTo('employees.email'))) checked @endif>
                <label class="custom-control-label" for="employees_email">Mitarbeiter per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('employees_email') d-block @enderror">
                @error('employees_email')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('employees_createpdf') is-invalid @enderror" name="employees_createpdf" id="employees_createpdf" value="true" @if(old('employees_createpdf', optional($permissions)->hasPermissionTo('employees.createpdf'))) checked @endif>
                <label class="custom-control-label" for="employees_createpdf">PDF Dateien von Mitarbeitern erstellen</label>
            </div>
            <div class="invalid-feedback @error('employees_createpdf') d-block @enderror">
                @error('employees_createpdf')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#users"></use>
            </svg>
            Personen
        </p>
        <p class="text-muted">
            Berechtigungen für die Personen.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('people_view') is-invalid @enderror" name="people_view" id="people_view" value="true" @if(old('people_view', optional($permissions)->hasPermissionTo('people.view'))) checked @endif>
                <label class="custom-control-label" for="people_view">Personen ansehen</label>
            </div>
            <div class="invalid-feedback @error('people_view') d-block @enderror">
                @error('people_view')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('people_create') is-invalid @enderror" name="people_create" id="people_create" value="true" @if(old('people_create', optional($permissions)->hasPermissionTo('people.create'))) checked @endif>
                <label class="custom-control-label" for="people_create">Personen anlegen</label>
            </div>
            <div class="invalid-feedback @error('people_create') d-block @enderror">
                @error('people_create')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('people_update') is-invalid @enderror" name="people_update" id="people_update" value="true" @if(old('people_update', optional($permissions)->hasPermissionTo('people.update'))) checked @endif>
                <label class="custom-control-label" for="people_update">Personen bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('people_update') d-block @enderror">
                @error('people_update')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('people_delete') is-invalid @enderror" name="people_delete" id="people_delete" value="true" @if(old('people_delete', optional($permissions)->hasPermissionTo('people.delete'))) checked @endif>
                <label class="custom-control-label" for="people_delete">Personen entfernen</label>
            </div>
            <div class="invalid-feedback @error('people_delete') d-block @enderror">
                @error('people_delete')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('people_email') is-invalid @enderror" name="people_email" id="people_email" value="true" @if(old('people_email', optional($permissions)->hasPermissionTo('people.email'))) checked @endif>
                <label class="custom-control-label" for="people_email">Personen per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('people_email') d-block @enderror">
                @error('people_email')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('people_createpdf') is-invalid @enderror" name="people_createpdf" id="people_createpdf" value="true" @if(old('people_createpdf', optional($permissions)->hasPermissionTo('people.createpdf'))) checked @endif>
                <label class="custom-control-label" for="people_createpdf">PDF Dateien von Personen erstellen</label>
            </div>
            <div class="invalid-feedback @error('people_createpdf') d-block @enderror">
                @error('people_createpdf')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
            </svg>
            Projekte
        </p>
        <p class="text-muted">
            Berechtigungen für die Projekte.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('projects_view') is-invalid @enderror" name="projects_view" id="projects_view" value="true" @if(old('projects_view', optional($permissions)->hasPermissionTo('projects.view'))) checked @endif>
                <label class="custom-control-label" for="projects_view">Projekte ansehen</label>
            </div>
            <div class="invalid-feedback @error('projects_view') d-block @enderror">
                @error('projects_view')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('projects_create') is-invalid @enderror" name="projects_create" id="projects_create" value="true" @if(old('projects_create', optional($permissions)->hasPermissionTo('projects.create'))) checked @endif>
                <label class="custom-control-label" for="projects_create">Projekte anlegen</label>
            </div>
            <div class="invalid-feedback @error('projects_create') d-block @enderror">
                @error('projects_create')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('projects_update') is-invalid @enderror" name="projects_update" id="projects_update" value="true" @if(old('projects_update', optional($permissions)->hasPermissionTo('projects.update'))) checked @endif>
                <label class="custom-control-label" for="projects_update">Projekte bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('projects_update') d-block @enderror">
                @error('projects_update')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('projects_delete') is-invalid @enderror" name="projects_delete" id="projects_delete" value="true" @if(old('projects_delete', optional($permissions)->hasPermissionTo('projects.delete'))) checked @endif>
                <label class="custom-control-label" for="projects_delete">Projekte entfernen</label>
            </div>
            <div class="invalid-feedback @error('projects_delete') d-block @enderror">
                @error('projects_delete')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('projects_email') is-invalid @enderror" name="projects_email" id="projects_email" value="true" @if(old('projects_email', optional($permissions)->hasPermissionTo('projects.email'))) checked @endif>
                <label class="custom-control-label" for="projects_email">Projekte per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('projects_email') d-block @enderror">
                @error('projects_email')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('projects_createpdf') is-invalid @enderror" name="projects_createpdf" id="projects_createpdf" value="true" @if(old('projects_createpdf', optional($permissions)->hasPermissionTo('projects.createpdf'))) checked @endif>
                <label class="custom-control-label" for="projects_createpdf">PDF Dateien von Projekten erstellen</label>
            </div>
            <div class="invalid-feedback @error('projects_createpdf') d-block @enderror">
                @error('projects_createpdf')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#key"></use>
            </svg>
            Rollen
        </p>
        <p class="text-muted">
            Berechtigungen für die Rollen.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('roles_view') is-invalid @enderror" name="roles_view" id="roles_view" value="true" @if(old('roles_view', optional($permissions)->hasPermissionTo('roles.view'))) checked @endif>
                <label class="custom-control-label" for="roles_view">Rollen (Sammlung von Berechtigungen) ansehen</label>
            </div>
            <div class="invalid-feedback @error('roles_view') d-block @enderror">
                @error('roles_view')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('roles_create') is-invalid @enderror" name="roles_create" id="roles_create" value="true" @if(old('roles_create', optional($permissions)->hasPermissionTo('roles.create'))) checked @endif>
                <label class="custom-control-label" for="roles_create">Rollen (Sammlung von Berechtigungen) erstellen</label>
            </div>
            <div class="invalid-feedback @error('roles_create') d-block @enderror">
                @error('roles_create')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('roles_update') is-invalid @enderror" name="roles_update" id="roles_update" value="true" @if(old('roles_update', optional($permissions)->hasPermissionTo('roles.update'))) checked @endif>
                <label class="custom-control-label" for="roles_update">Rollen (Sammlung von Berechtigungen) bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('roles_update') d-block @enderror">
                @error('roles_update')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('roles_delete') is-invalid @enderror" name="roles_delete" id="roles_delete" value="true" @if(old('roles_delete', optional($permissions)->hasPermissionTo('roles.delete'))) checked @endif>
                <label class="custom-control-label" for="roles_delete">Rollen (Sammlung von Berechtigungen) entfernen</label>
            </div>
            <div class="invalid-feedback @error('roles_delete') d-block @enderror">
                @error('roles_delete')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('roles_email') is-invalid @enderror" name="roles_email" id="roles_email" value="true" @if(old('roles_email', optional($permissions)->hasPermissionTo('roles.email'))) checked @endif>
                <label class="custom-control-label" for="roles_email">Rollen (Sammlung von Berechtigungen) per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('roles_email') d-block @enderror">
                @error('roles_email')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('roles_createpdf') is-invalid @enderror" name="roles_createpdf" id="roles_createpdf" value="true" @if(old('roles_createpdf', optional($permissions)->hasPermissionTo('roles.createpdf'))) checked @endif>
                <label class="custom-control-label" for="roles_createpdf">PDF Dateien von Rollen (Sammlung von Berechtigungen) erstellen</label>
            </div>
            <div class="invalid-feedback @error('roles_createpdf') d-block @enderror">
                @error('roles_createpdf')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
            </svg>
            Serviceberichte
        </p>
        <p class="text-muted">
            Berechtigungen für die Serviceberichte.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('service-reports_view_own') is-invalid @enderror" name="service-reports_view_own" id="service-reports_view_own" value="true" @if(old('service-reports_view_own', optional($permissions)->hasPermissionTo('service-reports.view.own'))) checked @endif>
                <label class="custom-control-label" for="service-reports_view_own">Eigene Serviceberichte ansehen</label>
            </div>
            <div class="invalid-feedback @error('service-reports_view_own') d-block @enderror">
                @error('service-reports_view_own')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('service-reports_view_other') is-invalid @enderror" name="service-reports_view_other" id="service-reports_view_other" value="true" @if(old('service-reports_view_other', optional($permissions)->hasPermissionTo('service-reports.view.other'))) checked @endif>
                <label class="custom-control-label" for="service-reports_view_other">Andere Serviceberichte ansehen</label>
            </div>
            <div class="invalid-feedback @error('service-reports_view_other') d-block @enderror">
                @error('service-reports_view_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('service-reports_create') is-invalid @enderror" name="service-reports_create" id="service-reports_create" value="true" @if(old('service-reports_create', optional($permissions)->hasPermissionTo('service-reports.create'))) checked @endif>
                <label class="custom-control-label" for="service-reports_create">Serviceberichte erstellen</label>
            </div>
            <div class="invalid-feedback @error('service-reports_create') d-block @enderror">
                @error('service-reports_create')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('service-reports_update_own') is-invalid @enderror" name="service-reports_update_own" id="service-reports_update_own" value="true" @if(old('service-reports_update_own', optional($permissions)->hasPermissionTo('service-reports.update.own'))) checked @endif>
                <label class="custom-control-label" for="service-reports_update_own">Eigene Serviceberichte bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('service-reports_update_own') d-block @enderror">
                @error('service-reports_update_own')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('service-reports_update_other') is-invalid @enderror" name="service-reports_update_other" id="service-reports_update_other" value="true" @if(old('service-reports_update_other', optional($permissions)->hasPermissionTo('service-reports.update.other'))) checked @endif>
                <label class="custom-control-label" for="service-reports_update_other">Andere Serviceberichte bearbeiten</label>
            </div>
            <div class="invalid-feedback @error('service-reports_update_other') d-block @enderror">
                @error('service-reports_update_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('service-reports_delete_own') is-invalid @enderror" name="service-reports_delete_own" id="service-reports_delete_own" value="true" @if(old('service-reports_delete_own', optional($permissions)->hasPermissionTo('service-reports.delete.own'))) checked @endif>
                <label class="custom-control-label" for="service-reports_delete_own">Eigene Serviceberichte entfernen (falls Status neu)</label>
            </div>
            <div class="invalid-feedback @error('service-reports_delete_own') d-block @enderror">
                @error('service-reports_delete_own')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('service-reports_delete_other') is-invalid @enderror" name="service-reports_delete_other" id="service-reports_delete_other" value="true" @if(old('service-reports_delete_other', optional($permissions)->hasPermissionTo('service-reports.delete.other'))) checked @endif>
                <label class="custom-control-label" for="service-reports_delete_other">Andere Serviceberichte entfernen (falls Status neu)</label>
            </div>
            <div class="invalid-feedback @error('service-reports_delete_other') d-block @enderror">
                @error('service-reports_delete_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('service-reports_email_own') is-invalid @enderror" name="service-reports_email_own" id="service-reports_email_own" value="true" @if(old('service-reports_email_own', optional($permissions)->hasPermissionTo('service-reports.email.own'))) checked @endif>
                <label class="custom-control-label" for="service-reports_email_own">Eigene Serviceberichte per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('service-reports_email_own') d-block @enderror">
                @error('service-reports_email_own')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('service-reports_email_other') is-invalid @enderror" name="service-reports_email_other" id="service-reports_email_other" value="true" @if(old('service-reports_email_other', optional($permissions)->hasPermissionTo('service-reports.email.other'))) checked @endif>
                <label class="custom-control-label" for="service-reports_email_other">Andere Serviceberichte per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('service-reports_email_other') d-block @enderror">
                @error('service-reports_email_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('service-reports_createpdf_own') is-invalid @enderror" name="service-reports_createpdf_own" id="service-reports_createpdf_own" value="true" @if(old('service-reports_createpdf_own', optional($permissions)->hasPermissionTo('service-reports.createpdf.own'))) checked @endif>
                <label class="custom-control-label" for="service-reports_createpdf_own">PDF Dateien von eigenen Serviceberichten erstellen</label>
            </div>
            <div class="invalid-feedback @error('service-reports_createpdf_own') d-block @enderror">
                @error('service-reports_createpdf_own')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('service-reports_createpdf_other') is-invalid @enderror" name="service-reports_createpdf_other" id="service-reports_createpdf_other" value="true" @if(old('service-reports_createpdf_other', optional($permissions)->hasPermissionTo('service-reports.createpdf.other'))) checked @endif>
                <label class="custom-control-label" for="service-reports_createpdf_other">PDF Dateien von anderen Serviceberichten erstellen</label>
            </div>
            <div class="invalid-feedback @error('service-reports_createpdf_other') d-block @enderror">
                @error('service-reports_createpdf_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('service-reports_send-signature-request_own') is-invalid @enderror" name="service-reports_send-signature-request_own" id="service-reports_send-signature-request_own" value="true" @if(old('service-reports_send-signature-request_own', optional($permissions)->hasPermissionTo('service-reports.send-signature-request.own'))) checked @endif>
                <label class="custom-control-label" for="service-reports_send-signature-request_own">Anfrage zur Unterschrift von eigenen Serviceberichten per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('service-reports_send-signature-request_own') d-block @enderror">
                @error('service-reports_send-signature-request_own')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('service-reports_send-signature-request_other') is-invalid @enderror" name="service-reports_send-signature-request_other" id="service-reports_send-signature-request_other" value="true" @if(old('service-reports_send-signature-request_other', optional($permissions)->hasPermissionTo('service-reports.send-signature-request.other'))) checked @endif>
                <label class="custom-control-label" for="service-reports_send-signature-request_other">Anfrage zur Unterschrift von anderen Serviceberichten per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('service-reports_send-signature-request_other') d-block @enderror">
                @error('service-reports_send-signature-request_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('service-reports_send-download-request_own') is-invalid @enderror" name="service-reports_send-download-request_own" id="service-reports_send-download-request_own" value="true" @if(old('service-reports_send-download-request_own', optional($permissions)->hasPermissionTo('service-reports.send-download-request.own'))) checked @endif>
                <label class="custom-control-label" for="service-reports_send-download-request_own">Anfrage zum Herunterladen von eigenen Serviceberichten per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('service-reports_send-download-request_own') d-block @enderror">
                @error('service-reports_send-download-request_own')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('service-reports_send-download-request_other') is-invalid @enderror" name="service-reports_send-download-request_other" id="service-reports_send-download-request_other" value="true" @if(old('service-reports_send-download-request_other', optional($permissions)->hasPermissionTo('service-reports.send-download-request.other'))) checked @endif>
                <label class="custom-control-label" for="service-reports_send-download-request_other">Anfrage zum Herunterladen von anderen Serviceberichten per E-Mail versenden</label>
            </div>
            <div class="invalid-feedback @error('service-reports_send-download-request_other') d-block @enderror">
                @error('service-reports_send-download-request_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('service-reports_get-signature_own') is-invalid @enderror" name="service-reports_get-signature_own" id="service-reports_get-signature_own" value="true" @if(old('service-reports_get-signature_own', optional($permissions)->hasPermissionTo('service-reports.get-signature.own'))) checked @endif>
                <label class="custom-control-label" for="service-reports_get-signature_own">Eigene Serviceberichte unterschreiben lassen</label>
            </div>
            <div class="invalid-feedback @error('service-reports_get-signature_own') d-block @enderror">
                @error('service-reports_get-signature_own')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('service-reports_get-signature_other') is-invalid @enderror" name="service-reports_get-signature_other" id="service-reports_get-signature_other" value="true" @if(old('service-reports_get-signature_other', optional($permissions)->hasPermissionTo('service-reports.get-signature.other'))) checked @endif>
                <label class="custom-control-label" for="service-reports_get-signature_other">Andere Serviceberichte unterschreiben lassen</label>
            </div>
            <div class="invalid-feedback @error('service-reports_get-signature_other') d-block @enderror">
                @error('service-reports_get-signature_other')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('service-reports_approve') is-invalid @enderror" name="service-reports_approve" id="service-reports_approve" value="true" @if(old('service-reports_approve', optional($permissions)->hasPermissionTo('service-reports.approve'))) checked @endif>
                <label class="custom-control-label" for="service-reports_approve">Serviceberichte als erledigt markieren</label>
            </div>
            <div class="invalid-feedback @error('service-reports_approve') d-block @enderror">
                @error('service-reports_approve')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <p class="d-inline-flex align-items-center mb-1">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#tool"></use>
            </svg>
            Tools
        </p>
        <p class="text-muted">
            Berechtigungen für die Tools.
        </p>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('tools_scanqr') is-invalid @enderror" name="tools_scanqr" id="tools_scanqr" value="true" @if(old('tools_scanqr', optional($permissions)->hasPermissionTo('tools.scanqr'))) checked @endif>
                <label class="custom-control-label" for="tools_scanqr">QR-Code Scanner verwenden</label>
            </div>
            <div class="invalid-feedback @error('tools_scanqr') d-block @enderror">
                @error('tools_scanqr')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
</div>
