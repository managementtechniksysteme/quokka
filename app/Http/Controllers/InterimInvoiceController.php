<?php

namespace App\Http\Controllers;

use App\Http\Requests\InterimInvoiceStoreRequest;
use App\Http\Requests\InterimInvoiceUpdateRequest;
use App\Models\ApplicationSettings;
use App\Models\InterimInvoice;
use App\Models\Project;

class InterimInvoiceController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(InterimInvoice::class, 'interim_invoice');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Project $project)
    {
        $currencyUnit = ApplicationSettings::get()->currency_unit;

        return view('interim_invoice.create')
            ->with(compact('project'))
            ->with('interimInvoice', null)
            ->with(compact('currencyUnit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InterimInvoiceStoreRequest $request, Project $project)
    {
        $interimInvoice = InterimInvoice::make($request->validated());

        $interimInvoice->project()->associate($project);

        $interimInvoice->save();

        return redirect()->route('projects.show', [$project, 'tab' => 'interim_invoices'])->with('success', 'Die Teilrechnung wurde erfolgreich angelegt.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, InterimInvoice $interimInvoice)
    {
        $interimInvoice->load('project');

        return view('interim_invoice.show')->with(compact('interimInvoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project, InterimInvoice $interimInvoice)
    {
        $interimInvoice->load('project');

        $currencyUnit = ApplicationSettings::get()->currency_unit;

        return view('interim_invoice.edit')
            ->with(compact('project'))
            ->with(compact('interimInvoice'))
            ->with(compact('currencyUnit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(InterimInvoiceUpdateRequest $request, Project $project, InterimInvoice $interimInvoice)
    {
        $interimInvoice->update($request->validated());

        return redirect()->route('projects.show', [$project, 'tab' => 'interim_invoices'])->with('success', 'Die Teilrechnung wurde erfolgreich bearbeitet.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project, InterimInvoice $interimInvoice)
    {
        $interimInvoice->delete();

        return redirect()->route('projects.show', [$project, 'tab' => 'interim_invoices'])->with('success', 'Die Teilrechnung wurde erfolgreich entfernt.');
    }
}
