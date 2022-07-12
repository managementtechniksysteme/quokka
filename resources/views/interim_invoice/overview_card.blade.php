<div class=" d-none d-md-block">
    @include ('interim_invoice.overview_card_content')
</div>

<gesture-links class="d-md-none" pan_right="{{ route('interim-invoices.edit', ['project' => $interimInvoice->project, 'interim_invoice' => $interimInvoice]) }}">
    @include ('interim_invoice.overview_card_content')
</gesture-links>
